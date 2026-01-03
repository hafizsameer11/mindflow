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

    public function index()
    {
        $feedbacks = Feedback::with(['patient.user', 'psychologist.user', 'appointment'])
            ->latest()
            ->paginate(15);
        
        return view('admin.reviews', compact('feedbacks'));
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->back()->withSuccess('Feedback deleted successfully.');
    }
}
