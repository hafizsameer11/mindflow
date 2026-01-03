<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PsychologistPrescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:psychologist']);
    }

    public function create(Appointment $appointment)
    {
        if ($appointment->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $appointment->load('patient.user');
        return view('psychologist.prescriptions.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'notes' => 'nullable|string',
            'therapy_plan' => 'nullable|string',
        ]);

        Prescription::create([
            'appointment_id' => $appointment->id,
            'psychologist_id' => $appointment->psychologist_id,
            'patient_id' => $appointment->patient_id,
            'notes' => $request->notes,
            'therapy_plan' => $request->therapy_plan,
        ]);

        return redirect()->route('psychologist.appointments.show', $appointment)
            ->withSuccess('Prescription created successfully.');
    }

    public function edit(Prescription $prescription)
    {
        if ($prescription->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $prescription->load(['appointment.patient.user']);
        return view('psychologist.prescriptions.edit', compact('prescription'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        if ($prescription->psychologist_id !== Auth::user()->psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'notes' => 'nullable|string',
            'therapy_plan' => 'nullable|string',
        ]);

        $prescription->update([
            'notes' => $request->notes,
            'therapy_plan' => $request->therapy_plan,
        ]);

        return redirect()->route('psychologist.appointments.show', $prescription->appointment)
            ->withSuccess('Prescription updated successfully.');
    }
}
