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
        
        $query = Appointment::with('patient.user')
            ->where('psychologist_id', $psychologist->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest()->paginate(15);
        
        return view('appointments', compact('appointments'));
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

        $appointment->update([
            'status' => 'cancelled',
            'notes' => $request->notes ?? $appointment->notes,
        ]);

        // Send notification
        app(NotificationService::class)->notifyAppointmentCancelled($appointment);

        return redirect()->back()->withSuccess('Appointment cancelled.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        if ($appointment->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
        ]);

        return redirect()->back()->withSuccess('Appointment rescheduled.');
    }
}
