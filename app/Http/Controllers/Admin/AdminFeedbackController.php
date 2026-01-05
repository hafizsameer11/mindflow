<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Feedback::with(['patient.user', 'psychologist.user', 'appointment']);

        // Search by patient or psychologist name/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient.user', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('psychologist.user', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $feedbacks = $query->latest()->get();

        // Calculate statistics
        $stats = [
            'total' => Feedback::count(),
            'average_rating' => Feedback::avg('rating') ? round(Feedback::avg('rating'), 2) : 0,
            'rating_5' => Feedback::where('rating', 5)->count(),
            'rating_4' => Feedback::where('rating', 4)->count(),
            'rating_3' => Feedback::where('rating', 3)->count(),
            'rating_2' => Feedback::where('rating', 2)->count(),
            'rating_1' => Feedback::where('rating', 1)->count(),
        ];

        return view('admin.reviews', compact('feedbacks', 'stats'));
    }

    public function show(Feedback $feedback)
    {
        $feedback->load(['patient.user', 'psychologist.user', 'appointment']);
        return view('admin.feedbacks.show', compact('feedback'));
    }

    public function destroy(Request $request, Feedback $feedback)
    {
        $request->validate([
            'delete_reason' => 'required|string|min:10',
        ]);

        // Log the deletion reason for audit purposes
        \Log::info('Feedback deleted by admin', [
            'feedback_id' => $feedback->id,
            'patient_id' => $feedback->patient_id,
            'psychologist_id' => $feedback->psychologist_id,
            'rating' => $feedback->rating,
            'delete_reason' => $request->delete_reason,
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        $feedback->delete();
        return redirect()->route('admin.feedbacks.index')->withSuccess('Review deleted successfully. System integrity maintained.');
    }
}
