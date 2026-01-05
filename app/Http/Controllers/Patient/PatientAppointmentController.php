<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Psychologist;
use App\Models\PsychologistAvailability;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function index(Request $request)
    {
        $patient = Auth::user()->patient;
        
        $baseQuery = Appointment::where('patient_id', $patient->id);
        
        $query = Appointment::with(['psychologist.user', 'payment'])
            ->where('patient_id', $patient->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest()->paginate(15);
        
        // Get counts for each status
        $stats = [
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')
                ->where('appointment_date', '>=', today())->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];
        
        return view('patient-appointments', compact('appointments', 'stats'));
    }

    public function create(Psychologist $psychologist)
    {
        if ($psychologist->verification_status !== 'verified') {
            abort(404);
        }

        // Load psychologist with user relationship
        $psychologist->load('user');

        // Get availabilities grouped by day
        $availabilities = PsychologistAvailability::where('psychologist_id', $psychologist->id)
            ->where('is_available', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        // Get existing appointments to check for booked slots
        $existingAppointments = Appointment::where('psychologist_id', $psychologist->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->get()
            ->map(function($apt) {
                return [
                    'date' => $apt->appointment_date->format('Y-m-d'),
                    'time' => $apt->appointment_time,
                ];
            });

        return view('booking', compact('psychologist', 'availabilities', 'existingAppointments'));
    }

    public function store(Request $request, Psychologist $psychologist)
    {
        $patient = Auth::user()->patient;

        if ($psychologist->verification_status !== 'verified') {
            abort(404);
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'duration' => 'nullable|integer|min:30|max:120',
        ]);

        // Check if time slot is available
        $dayOfWeek = Carbon::parse($request->appointment_date)->dayOfWeek;
        $availability = PsychologistAvailability::where('psychologist_id', $psychologist->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->where('start_time', '<=', $request->appointment_time)
            ->where('end_time', '>=', $request->appointment_time)
            ->first();

        if (!$availability) {
            return redirect()->back()->withErrors('Selected time slot is not available.');
        }

        // Check for conflicts
        $conflict = Appointment::where('psychologist_id', $psychologist->id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return redirect()->back()->withErrors('This time slot is already booked.');
        }

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'psychologist_id' => $psychologist->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'duration' => $request->duration ?? 60,
            'status' => 'pending',
            'consultation_type' => 'video',
        ]);

        // Send notification
        app(NotificationService::class)->notifyAppointmentCreated($appointment);

        return redirect()->route('patient.payment.create', $appointment)
            ->withSuccess('Appointment created. Please upload payment receipt.');
    }

    public function show(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        $appointment->load(['psychologist.user', 'payment', 'prescription', 'feedback']);
        
        return view('patient-appointment-details', compact('appointment'));
    }

    public function cancel(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status === 'completed') {
            return redirect()->back()->withErrors('Cannot cancel completed appointment.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->withSuccess('Appointment cancelled.');
    }
}
