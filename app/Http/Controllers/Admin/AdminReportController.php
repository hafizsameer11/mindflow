<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use App\Models\Psychologist;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Get date range options
        $dateRanges = [
            'today' => ['from' => Carbon::today(), 'to' => Carbon::today()],
            'yesterday' => ['from' => Carbon::yesterday(), 'to' => Carbon::yesterday()],
            'this_week' => ['from' => Carbon::now()->startOfWeek(), 'to' => Carbon::now()->endOfWeek()],
            'last_week' => ['from' => Carbon::now()->subWeek()->startOfWeek(), 'to' => Carbon::now()->subWeek()->endOfWeek()],
            'this_month' => ['from' => Carbon::now()->startOfMonth(), 'to' => Carbon::now()->endOfMonth()],
            'last_month' => ['from' => Carbon::now()->subMonth()->startOfMonth(), 'to' => Carbon::now()->subMonth()->endOfMonth()],
            'this_year' => ['from' => Carbon::now()->startOfYear(), 'to' => Carbon::now()->endOfYear()],
            'last_year' => ['from' => Carbon::now()->subYear()->startOfYear(), 'to' => Carbon::now()->subYear()->endOfYear()],
        ];

        return view('admin.reports.index', compact('dateRanges'));
    }

    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient.user', 'psychologist.user']);

        // Date range filter
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();
        
        $query->whereBetween('appointment_date', [$dateFrom, $dateTo]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('psychologist_id')) {
            $query->where('psychologist_id', $request->psychologist_id);
        }

        $appointments = $query->get();

        // Calculate statistics and trends
        $stats = [
            'total' => $appointments->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'completion_rate' => $appointments->count() > 0 
                ? round(($appointments->where('status', 'completed')->count() / $appointments->count()) * 100, 2) 
                : 0,
            'cancellation_rate' => $appointments->count() > 0 
                ? round(($appointments->where('status', 'cancelled')->count() / $appointments->count()) * 100, 2) 
                : 0,
        ];

        // Daily trend analysis
        $dailyTrends = [];
        $currentDate = $dateFrom->copy();
        while ($currentDate <= $dateTo) {
            $dayAppointments = $appointments->filter(function($apt) use ($currentDate) {
                return Carbon::parse($apt->appointment_date)->isSameDay($currentDate);
            });
            
            $dailyTrends[] = [
                'date' => $currentDate->format('Y-m-d'),
                'label' => $currentDate->format('M d'),
                'total' => $dayAppointments->count(),
                'completed' => $dayAppointments->where('status', 'completed')->count(),
                'cancelled' => $dayAppointments->where('status', 'cancelled')->count(),
            ];
            
            $currentDate->addDay();
        }

        // Psychologist performance
        $psychologistPerformance = $appointments->groupBy('psychologist_id')->map(function($group) {
            return [
                'psychologist' => $group->first()->psychologist->user->name,
                'total' => $group->count(),
                'completed' => $group->where('status', 'completed')->count(),
                'cancelled' => $group->where('status', 'cancelled')->count(),
                'completion_rate' => $group->count() > 0 
                    ? round(($group->where('status', 'completed')->count() / $group->count()) * 100, 2) 
                    : 0,
            ];
        })->sortByDesc('total')->take(10);

        $psychologists = Psychologist::with('user')->get();

        return view('admin.reports.appointments', compact('appointments', 'stats', 'dailyTrends', 'psychologistPerformance', 'psychologists', 'dateFrom', 'dateTo'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['appointment.patient.user', 'appointment.psychologist.user']);

        // Date range filter
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to) : Carbon::now()->endOfMonth();
        
        $query->whereBetween('created_at', [$dateFrom, $dateTo]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->get();

        // Calculate financial statistics
        $stats = [
            'total_payments' => $payments->count(),
            'total_revenue' => $payments->where('status', 'verified')->sum('amount'),
            'pending_amount' => $payments->where('status', 'pending_verification')->sum('amount'),
            'rejected_amount' => $payments->where('status', 'rejected')->sum('amount'),
            'verified_count' => $payments->where('status', 'verified')->count(),
            'pending_count' => $payments->where('status', 'pending_verification')->count(),
            'rejected_count' => $payments->where('status', 'rejected')->count(),
            'average_payment' => $payments->where('status', 'verified')->count() > 0 
                ? round($payments->where('status', 'verified')->avg('amount'), 2) 
                : 0,
        ];

        // Daily revenue trends
        $dailyRevenue = [];
        $currentDate = $dateFrom->copy();
        while ($currentDate <= $dateTo) {
            $dayPayments = $payments->filter(function($payment) use ($currentDate) {
                return Carbon::parse($payment->created_at)->isSameDay($currentDate);
            });
            
            $dailyRevenue[] = [
                'date' => $currentDate->format('Y-m-d'),
                'label' => $currentDate->format('M d'),
                'revenue' => $dayPayments->where('status', 'verified')->sum('amount'),
                'count' => $dayPayments->where('status', 'verified')->count(),
            ];
            
            $currentDate->addDay();
        }

        // Monthly comparison (if date range spans multiple months)
        $monthlyComparison = $payments->groupBy(function($payment) {
            return Carbon::parse($payment->created_at)->format('Y-m');
        })->map(function($group) {
            return [
                'month' => Carbon::parse($group->first()->created_at)->format('M Y'),
                'revenue' => $group->where('status', 'verified')->sum('amount'),
                'count' => $group->where('status', 'verified')->count(),
            ];
        })->sortBy('month');

        return view('admin.reports.payments', compact('payments', 'stats', 'dailyRevenue', 'monthlyComparison', 'dateFrom', 'dateTo'));
    }

    public function invoiceReport(Request $request)
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

        $payments = $query->latest()->get();
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');

        return view('admin.invoice-report', compact('payments', 'totalRevenue'));
    }

    public function users(Request $request)
    {
        // Date range filter for activity
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : Carbon::now()->subMonth();
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to) : Carbon::now();

        // User statistics
        $stats = [
            'total_users' => User::count(),
            'total_psychologists' => Psychologist::count(),
            'total_patients' => Patient::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'verified_psychologists' => Psychologist::where('verification_status', 'verified')->count(),
            'pending_psychologists' => Psychologist::where('verification_status', 'pending')->count(),
            'rejected_psychologists' => Psychologist::where('verification_status', 'rejected')->count(),
        ];

        // New user registrations trend
        $newUsersTrend = [];
        $currentDate = $dateFrom->copy();
        while ($currentDate <= $dateTo) {
            $dayUsers = User::whereDate('created_at', $currentDate)->get();
            
            $newUsersTrend[] = [
                'date' => $currentDate->format('Y-m-d'),
                'label' => $currentDate->format('M d'),
                'total' => $dayUsers->count(),
                'psychologists' => $dayUsers->where('role', 'psychologist')->count(),
                'patients' => $dayUsers->where('role', 'patient')->count(),
            ];
            
            $currentDate->addDay();
        }

        // User activity analysis - count users who have appointments in the period
        $patientUserIds = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->whereBetween('appointments.created_at', [$dateFrom, $dateTo])
            ->pluck('patients.user_id')
            ->unique();
        
        $psychologistUserIds = DB::table('appointments')
            ->join('psychologists', 'appointments.psychologist_id', '=', 'psychologists.id')
            ->whereBetween('appointments.created_at', [$dateFrom, $dateTo])
            ->pluck('psychologists.user_id')
            ->unique();
        
        $activeUsers = $patientUserIds->merge($psychologistUserIds)->unique()->count();

        // Top active users - combine patient and psychologist appointments
        $userAppointmentCounts = [];
        
        // Count appointments for patients
        $patientCounts = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->whereBetween('appointments.created_at', [$dateFrom, $dateTo])
            ->select('patients.user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('patients.user_id')
            ->get();
        
        foreach ($patientCounts as $count) {
            if (!isset($userAppointmentCounts[$count->user_id])) {
                $userAppointmentCounts[$count->user_id] = 0;
            }
            $userAppointmentCounts[$count->user_id] += $count->count;
        }
        
        // Count appointments for psychologists
        $psychologistCounts = DB::table('appointments')
            ->join('psychologists', 'appointments.psychologist_id', '=', 'psychologists.id')
            ->whereBetween('appointments.created_at', [$dateFrom, $dateTo])
            ->select('psychologists.user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('psychologists.user_id')
            ->get();
        
        foreach ($psychologistCounts as $count) {
            if (!isset($userAppointmentCounts[$count->user_id])) {
                $userAppointmentCounts[$count->user_id] = 0;
            }
            $userAppointmentCounts[$count->user_id] += $count->count;
        }
        
        // Get top users
        arsort($userAppointmentCounts);
        $topUserIds = array_slice(array_keys($userAppointmentCounts), 0, 10, true);
        
        $topActiveUsers = User::whereIn('id', $topUserIds)
            ->get()
            ->map(function($user) use ($userAppointmentCounts) {
                $user->appointments_count = $userAppointmentCounts[$user->id] ?? 0;
                return $user;
            })
            ->sortByDesc('appointments_count')
            ->values();

        // Role distribution
        $roleDistribution = [
            'admin' => User::where('role', 'admin')->count(),
            'psychologist' => User::where('role', 'psychologist')->count(),
            'patient' => User::where('role', 'patient')->count(),
        ];

        return view('admin.reports.users', compact('stats', 'newUsersTrend', 'activeUsers', 'topActiveUsers', 'roleDistribution', 'dateFrom', 'dateTo'));
    }

    public function trends(Request $request)
    {
        // Default to last 6 months
        $dateFrom = $request->filled('date_from') 
            ? Carbon::parse($request->date_from) 
            : Carbon::now()->subMonths(6)->startOfMonth();
        $dateTo = $request->filled('date_to') 
            ? Carbon::parse($request->date_to) 
            : Carbon::now()->endOfMonth();

        // Monthly trends
        $monthlyTrends = [];
        $currentMonth = $dateFrom->copy()->startOfMonth();
        
        while ($currentMonth <= $dateTo) {
            $monthStart = $currentMonth->copy()->startOfMonth();
            $monthEnd = $currentMonth->copy()->endOfMonth();
            
            $monthAppointments = Appointment::whereBetween('appointment_date', [$monthStart, $monthEnd])->get();
            $monthPayments = Payment::whereBetween('created_at', [$monthStart, $monthEnd])->get();
            $monthUsers = User::whereBetween('created_at', [$monthStart, $monthEnd])->get();
            
            $monthlyTrends[] = [
                'month' => $currentMonth->format('Y-m'),
                'label' => $currentMonth->format('M Y'),
                'appointments' => $monthAppointments->count(),
                'completed_appointments' => $monthAppointments->where('status', 'completed')->count(),
                'revenue' => $monthPayments->where('status', 'verified')->sum('amount'),
                'new_users' => $monthUsers->count(),
                'new_psychologists' => $monthUsers->where('role', 'psychologist')->count(),
                'new_patients' => $monthUsers->where('role', 'patient')->count(),
            ];
            
            $currentMonth->addMonth();
        }

        // Growth rates
        $previousPeriod = [
            'appointments' => Appointment::whereBetween('appointment_date', [
                $dateFrom->copy()->subMonths(6)->startOfMonth(),
                $dateFrom->copy()->subDay()
            ])->count(),
            'revenue' => Payment::whereBetween('created_at', [
                $dateFrom->copy()->subMonths(6)->startOfMonth(),
                $dateFrom->copy()->subDay()
            ])->where('status', 'verified')->sum('amount'),
            'users' => User::whereBetween('created_at', [
                $dateFrom->copy()->subMonths(6)->startOfMonth(),
                $dateFrom->copy()->subDay()
            ])->count(),
        ];

        $currentPeriod = [
            'appointments' => Appointment::whereBetween('appointment_date', [$dateFrom, $dateTo])->count(),
            'revenue' => Payment::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'verified')->sum('amount'),
            'users' => User::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
        ];

        $growthRates = [];
        foreach (['appointments', 'revenue', 'users'] as $metric) {
            if ($previousPeriod[$metric] > 0) {
                $growthRates[$metric] = round((($currentPeriod[$metric] - $previousPeriod[$metric]) / $previousPeriod[$metric]) * 100, 2);
            } else {
                $growthRates[$metric] = $currentPeriod[$metric] > 0 ? 100 : 0;
            }
        }

        // Key insights
        $insights = [
            'peak_appointment_day' => $this->getPeakDay('appointments', $dateFrom, $dateTo),
            'peak_revenue_day' => $this->getPeakDay('revenue', $dateFrom, $dateTo),
            'most_active_psychologist' => $this->getMostActivePsychologist($dateFrom, $dateTo),
            'average_session_duration' => round(Appointment::whereBetween('appointment_date', [$dateFrom, $dateTo])->avg('duration'), 0),
        ];

        return view('admin.reports.trends', compact('monthlyTrends', 'growthRates', 'insights', 'dateFrom', 'dateTo'));
    }

    private function getPeakDay($type, $dateFrom, $dateTo)
    {
        if ($type === 'appointments') {
            $appointments = Appointment::whereBetween('appointment_date', [$dateFrom, $dateTo])->get();
            $dayCounts = $appointments->groupBy(function($apt) {
                return Carbon::parse($apt->appointment_date)->format('l'); // Day name
            })->map->count()->sortDesc();
            
            return $dayCounts->keys()->first() ?? 'N/A';
        } else {
            $payments = Payment::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'verified')
                ->get();
            $dayRevenue = $payments->groupBy(function($payment) {
                return Carbon::parse($payment->created_at)->format('l');
            })->map(function($group) {
                return $group->sum('amount');
            })->sortDesc();
            
            return $dayRevenue->keys()->first() ?? 'N/A';
        }
    }

    private function getMostActivePsychologist($dateFrom, $dateTo)
    {
        $psychologist = Psychologist::with('user')
            ->withCount(['appointments' => function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('appointment_date', [$dateFrom, $dateTo])
                  ->where('status', 'completed');
            }])
            ->orderBy('appointments_count', 'desc')
            ->first();
        
        return $psychologist ? $psychologist->user->name : 'N/A';
    }
}
