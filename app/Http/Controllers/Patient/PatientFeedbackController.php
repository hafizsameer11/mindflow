<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function create(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status !== 'completed') {
            return redirect()->back()->withErrors('Can only provide feedback for completed appointments.');
        }

        if ($appointment->feedback) {
            return redirect()->route('patient.feedback.edit', $appointment->feedback);
        }

        $appointment->load('psychologist.user');
        
        return view('patient.feedback.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status !== 'completed') {
            return redirect()->back()->withErrors('Can only provide feedback for completed appointments.');
        }

        if ($appointment->feedback) {
            return redirect()->back()->withErrors('Feedback already submitted.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Feedback::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'psychologist_id' => $appointment->psychologist_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('patient.appointments.show', $appointment)
            ->withSuccess('Feedback submitted successfully.');
    }

    public function edit(Feedback $feedback)
    {
        $patient = Auth::user()->patient;

        if ($feedback->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        $feedback->load(['appointment.psychologist.user']);
        
        return view('patient.feedback.edit', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $patient = Auth::user()->patient;

        if ($feedback->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $feedback->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('patient.appointments.show', $feedback->appointment)
            ->withSuccess('Feedback updated successfully.');
    }
}
