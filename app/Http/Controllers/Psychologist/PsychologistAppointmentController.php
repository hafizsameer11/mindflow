<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PsychologistAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:psychologist']);
    }

    public function index(Request $request)
    {
        $psychologist = Auth::user()->psychologist;
        
        $baseQuery = Appointment::where('psychologist_id', $psychologist->id);
        
        $query = Appointment::with('patient.user')
            ->where('psychologist_id', $psychologist->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Status filter
        $status = $request->input('status', 'upcoming');
        
        if ($status === 'upcoming') {
            $query->whereIn('status', ['pending', 'confirmed'])
                  ->where('appointment_date', '>=', today());
        } elseif ($status === 'ongoing') {
            $query->where('status', 'confirmed')
                  ->where('appointment_date', today())
                  ->where('appointment_time', '<=', now()->format('H:i:s'))
                  ->where('appointment_time', '>=', now()->subHours(2)->format('H:i:s'));
        } elseif ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($status === 'cancelled') {
            $query->where('status', 'cancelled');
        } else {
            $query->where('status', $status);
        }

        $appointments = $query->latest('appointment_date')->latest('appointment_time')->get();
        
        // Get statistics
        $stats = [
            'upcoming' => (clone $baseQuery)->whereIn('status', ['pending', 'confirmed'])
                ->where('appointment_date', '>=', today())->count(),
            'ongoing' => (clone $baseQuery)->where('status', 'confirmed')
                ->where('appointment_date', today())
                ->where('appointment_time', '<=', now()->format('H:i:s'))
                ->where('appointment_time', '>=', now()->subHours(2)->format('H:i:s'))->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];
        
        return view('appointments', compact('appointments', 'stats', 'status'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'payment', 'prescription', 'feedback']);
        
        if ($appointment->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        return view('doctor-appointment-details', compact('appointment'));
    }

    public function confirm(Appointment $appointment)
    {
        if ($appointment->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $appointment->update(['status' => 'confirmed']);
        
        // Send notification
        app(NotificationService::class)->notifyAppointmentConfirmed($appointment);
        
        return redirect()->back()->withSuccess('Appointment confirmed.');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status === 'completed') {
            return redirect()->back()->withErrors('Cannot cancel a completed appointment.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'notes' => ($request->cancellation_reason ? "Cancelled: " . $request->cancellation_reason . "\n" : "") . ($appointment->notes ?? ''),
        ]);

        // Send notification
        app(NotificationService::class)->notifyAppointmentCancelled($appointment);

        return redirect()->back()->withSuccess('Appointment cancelled successfully.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        if ($appointment->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reschedule_reason' => 'nullable|string|max:500',
        ]);

        // Check for conflicts
        $conflict = Appointment::where('psychologist_id', $appointment->psychologist_id)
            ->where('id', '!=', $appointment->id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return redirect()->back()->withErrors('This time slot is already booked.');
        }

        $oldDate = $appointment->appointment_date;
        $oldTime = $appointment->appointment_time;

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => ($request->reschedule_reason ? "Rescheduled: " . $request->reschedule_reason . "\n" : "") . ($appointment->notes ?? ''),
        ]);

        // Send notification
        app(NotificationService::class)->notifyAppointmentRescheduled($appointment, $oldDate, $oldTime);

        return redirect()->back()->withSuccess('Appointment rescheduled successfully.');
    }

    public function verifyPayment(Request $request, \App\Models\Payment $payment)
    {
        $psychologist = Auth::user()->psychologist;

        // Verify the payment belongs to this psychologist's appointment
        if ($payment->appointment->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        if ($payment->status !== 'pending_verification') {
            return redirect()->back()->withErrors('Payment is not pending verification.');
        }

        $payment->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update appointment status to confirmed if payment is verified
        $payment->appointment->update(['status' => 'confirmed']);

        // Send notification
        app(NotificationService::class)->notifyPaymentVerified($payment);
        app(NotificationService::class)->notifyAppointmentConfirmed($payment->appointment);

        return redirect()->back()->withSuccess('Payment verified successfully. Appointment confirmed.');
    }

    public function rejectPayment(Request $request, \App\Models\Payment $payment)
    {
        $psychologist = Auth::user()->psychologist;

        // Verify the payment belongs to this psychologist's appointment
        if ($payment->appointment->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        if ($payment->status !== 'pending_verification') {
            return redirect()->back()->withErrors('Payment is not pending verification.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Send notification
        app(NotificationService::class)->notifyPaymentRejected($payment);

        return redirect()->back()->withSuccess('Payment rejected.');
    }
}
