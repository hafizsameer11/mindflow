<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PsychologistEarningsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:psychologist']);
    }

    public function index(Request $request)
    {
        $psychologist = Auth::user()->psychologist;
        
        $query = Payment::with(['appointment.patient.user'])
            ->whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            });

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->get();

        // Calculate statistics
        $totalEarnings = Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->where('status', 'verified')
            ->sum('amount');

        $pendingEarnings = Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->where('status', 'pending_verification')
            ->sum('amount');

        $thisMonthEarnings = Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->where('status', 'verified')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $totalSessions = Appointment::where('psychologist_id', $psychologist->id)
            ->where('status', 'completed')
            ->count();

        $verifiedPayments = Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->where('status', 'verified')
            ->count();

        // Monthly earnings chart data
        $monthlyEarnings = Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->where('status', 'verified')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $stats = [
            'total_earnings' => $totalEarnings,
            'pending_earnings' => $pendingEarnings,
            'this_month_earnings' => $thisMonthEarnings,
            'total_sessions' => $totalSessions,
            'verified_payments' => $verifiedPayments,
            'monthly_earnings' => $monthlyEarnings,
        ];

        return view('psychologist.earnings.index', compact('payments', 'stats'));
    }
}
