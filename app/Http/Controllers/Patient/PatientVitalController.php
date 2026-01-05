<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\PatientVital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientVitalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function index()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('patient.dashboard')->withErrors('Patient profile not found.');
        }

        $vitals = PatientVital::where('patient_id', $patient->id)
            ->latest('recorded_date')
            ->latest('created_at')
            ->get();

        $latestVital = $vitals->first();

        return view('medical-details', compact('vitals', 'latestVital'));
    }

    public function store(Request $request)
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->back()->withErrors('Patient profile not found.');
        }

        $request->validate([
            'bmi' => 'nullable|string|max:50',
            'heart_rate' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'fbc' => 'nullable|string|max:50',
            'blood_pressure' => 'nullable|string|max:50',
            'glucose_level' => 'nullable|string|max:50',
            'body_temperature' => 'nullable|string|max:50',
            'recorded_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        PatientVital::create([
            'patient_id' => $patient->id,
            'bmi' => $request->bmi,
            'heart_rate' => $request->heart_rate,
            'weight' => $request->weight,
            'fbc' => $request->fbc,
            'blood_pressure' => $request->blood_pressure,
            'glucose_level' => $request->glucose_level,
            'body_temperature' => $request->body_temperature,
            'recorded_date' => $request->recorded_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('medical-details')->withSuccess('Vitals recorded successfully.');
    }
}
