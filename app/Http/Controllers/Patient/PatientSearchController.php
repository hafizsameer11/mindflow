<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Psychologist;
use Illuminate\Http\Request;

class PatientSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Psychologist::with('user')
            ->where('verification_status', 'verified')
            ->withCount(['feedbacks as avg_rating' => function($q) {
                $q->selectRaw('COALESCE(AVG(rating), 0)');
            }]);

        // Search by name or specialization
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        // Filter by experience
        if ($request->filled('min_experience')) {
            $query->where('experience_years', '>=', $request->min_experience);
        }
        if ($request->filled('max_experience')) {
            $query->where('experience_years', '<=', $request->max_experience);
        }

        // Filter by fee range
        if ($request->filled('min_fee')) {
            $query->where('consultation_fee', '>=', $request->min_fee);
        }
        if ($request->filled('max_fee')) {
            $query->where('consultation_fee', '<=', $request->max_fee);
        }

        // Filter by minimum rating
        if ($request->filled('min_rating')) {
            $query->whereRaw('(SELECT COALESCE(AVG(rating), 0) FROM feedbacks WHERE psychologist_id = psychologists.id) >= ?', [$request->min_rating]);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        
        switch ($sortBy) {
            case 'fee_low':
                $query->orderBy('consultation_fee', 'asc');
                break;
            case 'fee_high':
                $query->orderBy('consultation_fee', 'desc');
                break;
            case 'experience':
                $query->orderBy('experience_years', 'desc');
                break;
            case 'rating':
                $query->orderByRaw('(SELECT AVG(rating) FROM feedbacks WHERE psychologist_id = psychologists.id) DESC');
                break;
            case 'name':
            default:
                $query->join('users', 'psychologists.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'asc')
                      ->select('psychologists.*');
                break;
        }

        $psychologists = $query->paginate(12)->appends($request->query());

        // Get all specializations for filter dropdown
        $specializations = Psychologist::where('verification_status', 'verified')
            ->distinct()
            ->pluck('specialization')
            ->filter()
            ->sort()
            ->values();

        // Get min/max values for filters
        $stats = [
            'min_fee' => Psychologist::where('verification_status', 'verified')->min('consultation_fee') ?? 0,
            'max_fee' => Psychologist::where('verification_status', 'verified')->max('consultation_fee') ?? 1000,
            'min_experience' => Psychologist::where('verification_status', 'verified')->min('experience_years') ?? 0,
            'max_experience' => Psychologist::where('verification_status', 'verified')->max('experience_years') ?? 50,
        ];

        return view('search', compact('psychologists', 'specializations', 'stats'));
    }

    public function show(Psychologist $psychologist)
    {
        if ($psychologist->verification_status !== 'verified') {
            abort(404);
        }

        $psychologist->load(['user', 'availabilities', 'feedbacks.patient.user']);
        
        // Get average rating and rating breakdown
        $avgRating = $psychologist->feedbacks()->avg('rating') ?? 0;
        $totalReviews = $psychologist->feedbacks()->count();
        
        $ratingBreakdown = $psychologist->feedbacks()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();
        
        // Get recent reviews
        $recentReviews = $psychologist->feedbacks()
            ->with('patient.user')
            ->latest()
            ->take(5)
            ->get();
        
        // Get total appointments
        $totalAppointments = $psychologist->appointments()->count();
        $completedAppointments = $psychologist->appointments()->where('status', 'completed')->count();

        return view('patient-psychologist-profile', compact('psychologist', 'avgRating', 'totalReviews', 'ratingBreakdown', 'recentReviews', 'totalAppointments', 'completedAppointments'));
    }
}
