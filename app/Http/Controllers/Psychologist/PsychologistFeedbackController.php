<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Psychologist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PsychologistFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:psychologist']);
    }

    public function index(Request $request)
    {
        $psychologist = Auth::user()->psychologist;
        
        $query = Feedback::with(['patient.user', 'appointment'])
            ->where('psychologist_id', $psychologist->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('comment', 'like', "%{$search}%");
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $feedbacks = $query->latest()->get();

        // Calculate statistics
        $stats = [
            'total' => Feedback::where('psychologist_id', $psychologist->id)->count(),
            'average_rating' => Feedback::where('psychologist_id', $psychologist->id)->avg('rating') ? round(Feedback::where('psychologist_id', $psychologist->id)->avg('rating'), 2) : 0,
            'rating_5' => Feedback::where('psychologist_id', $psychologist->id)->where('rating', 5)->count(),
            'rating_4' => Feedback::where('psychologist_id', $psychologist->id)->where('rating', 4)->count(),
            'rating_3' => Feedback::where('psychologist_id', $psychologist->id)->where('rating', 3)->count(),
            'rating_2' => Feedback::where('psychologist_id', $psychologist->id)->where('rating', 2)->count(),
            'rating_1' => Feedback::where('psychologist_id', $psychologist->id)->where('rating', 1)->count(),
        ];

        return view('psychologist.feedback.index', compact('feedbacks', 'stats'));
    }

    public function show(Feedback $feedback)
    {
        $psychologist = Auth::user()->psychologist;
        
        if ($feedback->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $feedback->load(['patient.user', 'appointment']);
        return view('psychologist.feedback.show', compact('feedback'));
    }
}
