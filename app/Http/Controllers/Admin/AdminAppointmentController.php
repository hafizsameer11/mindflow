<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'psychologist.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->latest()->paginate(15);
        return view('admin.appointment-list', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'psychologist.user', 'payment', 'prescription', 'feedback']);
        return view('admin.appointments.show', compact('appointment'));
    }
}
