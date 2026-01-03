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

    public function index()
    {
        $psychologists = Psychologist::with('user')->paginate(15);
        return view('admin.doctor-list', compact('psychologists'));
    }

    public function show(Psychologist $psychologist)
    {
        $psychologist->load('user');
        return view('admin.psychologists.show', compact('psychologist'));
    }

    public function verify(Psychologist $psychologist)
    {
        $psychologist->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        // Send notification
        app(NotificationService::class)->notifyPsychologistVerified($psychologist);

        return redirect()->back()->withSuccess('Psychologist verified successfully.');
    }

    public function reject(Request $request, Psychologist $psychologist)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $psychologist->update([
            'verification_status' => 'rejected',
        ]);

        // Send notification
        app(NotificationService::class)->notifyPsychologistRejected($psychologist);

        return redirect()->back()->withSuccess('Psychologist verification rejected.');
    }

    public function destroy(Psychologist $psychologist)
    {
        $psychologist->user->delete();
        return redirect()->route('admin.psychologists.index')->withSuccess('Psychologist deleted successfully.');
    }
}
