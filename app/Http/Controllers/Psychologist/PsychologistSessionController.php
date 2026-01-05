<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PsychologistSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:psychologist']);
    }

    public function start(Appointment $appointment)
    {
        $psychologist = Auth::user()->psychologist;

        if ($appointment->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status !== 'confirmed') {
            return redirect()->back()->withErrors('Appointment must be confirmed to start session.');
        }

        // Generate meeting link if not exists using VideoCallService
        if (!$appointment->meeting_link) {
            $videoCallService = app(\App\Services\VideoCallService::class);
            $meetingLink = $videoCallService->generateMeetingLink($appointment);
        }

        $appointment->refresh();
        $appointment->load('patient.user');
        
        return view('doctor-appointment-start', compact('appointment'));
    }

    public function end(Request $request, Appointment $appointment)
    {
        $psychologist = Auth::user()->psychologist;

        if ($appointment->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'session_notes' => 'nullable|string|max:5000',
            'diagnosis' => 'nullable|string|max:2000',
            'observations' => 'nullable|string|max:3000',
            'follow_up_recommendations' => 'nullable|string|max:2000',
        ]);

        $appointment->update([
            'status' => 'completed',
            'session_notes' => $request->session_notes,
            'diagnosis' => $request->diagnosis,
            'observations' => $request->observations,
            'follow_up_recommendations' => $request->follow_up_recommendations,
        ]);

        return redirect()->route('psychologist.appointments.show', $appointment)
            ->withSuccess('Session ended and notes saved successfully.');
    }

    public function saveNotes(Request $request, Appointment $appointment)
    {
        $psychologist = Auth::user()->psychologist;

        if ($appointment->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'session_notes' => 'nullable|string|max:5000',
            'diagnosis' => 'nullable|string|max:2000',
            'observations' => 'nullable|string|max:3000',
            'follow_up_recommendations' => 'nullable|string|max:2000',
        ]);

        $appointment->update([
            'session_notes' => $request->session_notes,
            'diagnosis' => $request->diagnosis,
            'observations' => $request->observations,
            'follow_up_recommendations' => $request->follow_up_recommendations,
        ]);

        return redirect()->back()->withSuccess('Session notes saved successfully.');
    }
}
