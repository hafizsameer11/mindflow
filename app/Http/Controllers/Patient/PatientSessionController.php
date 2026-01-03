<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function join(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status !== 'confirmed') {
            return redirect()->back()->withErrors('Appointment must be confirmed to join session.');
        }

        // Check if payment is verified
        if (!$appointment->payment || $appointment->payment->status !== 'verified') {
            return redirect()->back()->withErrors('Payment must be verified before joining session.');
        }

        if (!$appointment->meeting_link) {
            return redirect()->back()->withErrors('Meeting link not generated yet. Please wait for psychologist to start the session.');
        }

        $appointment->load('psychologist.user');
        
        return view('video-call', compact('appointment'));
    }
}
