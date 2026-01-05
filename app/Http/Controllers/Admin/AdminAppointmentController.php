<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'psychologist.user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Search by patient or psychologist name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('psychologist.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $appointments = $query->latest()->get();

        // Calculate statistics and patterns
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        // Calculate missed sessions (confirmed but not completed, past appointment date)
        $today = now()->startOfDay();
        $missedSessions = Appointment::where('status', 'confirmed')
            ->where('appointment_date', '<', $today)
            ->count();
        $stats['missed'] = $missedSessions;

        // Find patients with repeated cancellations (3+ cancellations)
        $patientsWithRepeatedCancellations = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->where('appointments.status', 'cancelled')
            ->select('users.name', 'users.email', 'patients.id as patient_id', DB::raw('COUNT(*) as cancellation_count'))
            ->groupBy('patients.id', 'users.name', 'users.email')
            ->having('cancellation_count', '>=', 3)
            ->orderBy('cancellation_count', 'desc')
            ->get();

        // Calculate cancellation rate
        $cancellationRate = $stats['total'] > 0 
            ? round(($stats['cancelled'] / $stats['total']) * 100, 2) 
            : 0;

        return view('admin.appointment-list', compact('appointments', 'stats', 'patientsWithRepeatedCancellations', 'cancellationRate'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'psychologist.user', 'payment', 'prescription', 'feedback']);
        return view('admin.appointments.show', compact('appointment'));
    }
}
