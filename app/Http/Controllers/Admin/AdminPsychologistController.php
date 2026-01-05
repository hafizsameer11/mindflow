<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Psychologist;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPsychologistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Psychologist::with('user');

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        } else {
            // Default: show pending first
            $query->orderByRaw("CASE WHEN verification_status = 'pending' THEN 0 WHEN verification_status = 'rejected' THEN 1 ELSE 2 END");
        }

        // Search by name or specialization
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $psychologists = $query->latest()->get();
        return view('admin.doctor-list', compact('psychologists'));
    }

    public function show(Psychologist $psychologist)
    {
        $psychologist->load('user');
        
        // Get statistics
        $stats = [
            'total_appointments' => $psychologist->appointments()->count(),
            'completed_appointments' => $psychologist->appointments()->where('status', 'completed')->count(),
            'total_earnings' => \App\Models\Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })->where('status', 'verified')->sum('amount'),
            'average_rating' => $psychologist->feedbacks()->avg('rating') ?? 0,
            'total_reviews' => $psychologist->feedbacks()->count(),
        ];

        return view('admin.psychologists.show', compact('psychologist', 'stats'));
    }

    public function verify(Psychologist $psychologist)
    {
        $psychologist->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        // Send notification
        if (class_exists(NotificationService::class)) {
            app(NotificationService::class)->notifyPsychologistVerified($psychologist);
        }

        return redirect()->back()->withSuccess('Psychologist verified successfully. The psychologist can now accept appointments.');
    }

    public function reject(Request $request, Psychologist $psychologist)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
            'rejection_reason.min' => 'Rejection reason must be at least 10 characters.',
        ]);

        $psychologist->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Send notification
        if (class_exists(NotificationService::class)) {
            app(NotificationService::class)->notifyPsychologistRejected($psychologist);
        }

        return redirect()->back()->withSuccess('Psychologist verification rejected. The psychologist has been notified.');
    }

    public function destroy(Psychologist $psychologist)
    {
        $psychologist->user->delete();
        return redirect()->route('admin.psychologists.index')->withSuccess('Psychologist deleted successfully.');
    }
}
