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

    public function index(Request $request)
    {
        $psychologist = Auth::user()->psychologist;
        
        if (!$psychologist) {
            abort(404, 'Psychologist profile not found');
        }
        
        $query = Prescription::with(['appointment.patient.user', 'appointment'])
            ->where('psychologist_id', $psychologist->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('appointment.patient.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('notes', 'like', "%{$search}%")
              ->orWhere('therapy_plan', 'like', "%{$search}%");
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $prescriptions = $query->latest()->get();

        // Calculate statistics
        $stats = [
            'total' => Prescription::where('psychologist_id', $psychologist->id)->count(),
            'this_month' => Prescription::where('psychologist_id', $psychologist->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'with_therapy_plan' => Prescription::where('psychologist_id', $psychologist->id)
                ->whereNotNull('therapy_plan')
                ->where('therapy_plan', '!=', '')
                ->count(),
            'with_notes' => Prescription::where('psychologist_id', $psychologist->id)
                ->whereNotNull('notes')
                ->where('notes', '!=', '')
                ->count(),
        ];

        return view('psychologist.prescriptions.index', compact('prescriptions', 'stats'));
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
