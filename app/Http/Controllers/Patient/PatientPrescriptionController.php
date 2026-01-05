<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientPrescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function index()
    {
        $patient = Auth::user()->patient;
        
        $prescriptions = Prescription::with(['appointment.psychologist.user'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->get();

        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        $patient = Auth::user()->patient;

        if ($prescription->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        $prescription->load(['appointment.psychologist.user']);
        
        return view('patient.prescriptions.show', compact('prescription'));
    }
}
