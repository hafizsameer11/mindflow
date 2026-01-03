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
            ->where('verification_status', 'verified');

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('specialization', 'like', "%{$search}%");
        }

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        // Filter by fee range
        if ($request->filled('min_fee')) {
            $query->where('consultation_fee', '>=', $request->min_fee);
        }
        if ($request->filled('max_fee')) {
            $query->where('consultation_fee', '<=', $request->max_fee);
        }

        // Filter by rating (if feedback system is implemented)
        // This would require joining with feedbacks table

        $psychologists = $query->paginate(12);

        $specializations = Psychologist::where('verification_status', 'verified')
            ->distinct()
            ->pluck('specialization')
            ->filter();

        return view('search', compact('psychologists', 'specializations'));
    }

    public function show(Psychologist $psychologist)
    {
        if ($psychologist->verification_status !== 'verified') {
            abort(404);
        }

        $psychologist->load('user');
        
        // Get average rating
        $avgRating = $psychologist->feedbacks()->avg('rating') ?? 0;
        $totalReviews = $psychologist->feedbacks()->count();

        return view('doctor-profile-2', compact('psychologist', 'avgRating', 'totalReviews'));
    }
}
