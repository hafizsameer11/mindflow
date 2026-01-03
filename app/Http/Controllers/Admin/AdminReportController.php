<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use App\Models\Psychologist;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient.user', 'psychologist.user']);

        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->get();

        return view('admin.reports.appointments', compact('appointments'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['appointment.patient.user', 'appointment.psychologist.user']);

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->get();
        $totalRevenue = $payments->where('status', 'verified')->sum('amount');

        return view('admin.reports.payments', compact('payments', 'totalRevenue'));
    }

    public function users()
    {
        $stats = [
            'total_users' => User::count(),
            'total_psychologists' => Psychologist::count(),
            'total_patients' => Patient::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'verified_psychologists' => Psychologist::where('verification_status', 'verified')->count(),
            'pending_psychologists' => Psychologist::where('verification_status', 'pending')->count(),
        ];

        return view('admin.reports.users', compact('stats'));
    }
}
