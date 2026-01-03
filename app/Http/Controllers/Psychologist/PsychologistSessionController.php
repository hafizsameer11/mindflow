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

    public function end(Appointment $appointment)
    {
        $psychologist = Auth::user()->psychologist;

        if ($appointment->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $appointment->update(['status' => 'completed']);

        return redirect()->route('psychologist.appointments.show', $appointment)
            ->withSuccess('Session ended successfully.');
    }
}
