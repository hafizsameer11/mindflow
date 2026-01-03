<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Psychologist;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_psychologists' => Psychologist::count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
            'pending_verifications' => Psychologist::where('verification_status', 'pending')->count(),
            'pending_payments' => Payment::where('status', 'pending_verification')->count(),
        ];

        $recent_appointments = Appointment::with(['patient.user', 'psychologist.user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.index_admin', compact('stats', 'recent_appointments'));
    }
}
