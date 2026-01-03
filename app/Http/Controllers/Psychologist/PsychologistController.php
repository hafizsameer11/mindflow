<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Psychologist;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PsychologistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:psychologist']);
    }

    public function dashboard()
    {
        $psychologist = Auth::user()->psychologist;
        
        if (!$psychologist) {
            return redirect()->route('psychologist.profile.create');
        }

        $stats = [
            'total_patients' => Appointment::where('psychologist_id', $psychologist->id)
                ->distinct('patient_id')
                ->count(),
            'appointments_today' => Appointment::where('psychologist_id', $psychologist->id)
                ->where('appointment_date', today())
                ->count(),
            'upcoming_appointments' => Appointment::where('psychologist_id', $psychologist->id)
                ->where('status', 'confirmed')
                ->where('appointment_date', '>=', today())
                ->count(),
            'completed_appointments' => Appointment::where('psychologist_id', $psychologist->id)
                ->where('status', 'completed')
                ->count(),
        ];

        $recent_appointments = Appointment::with('patient.user')
            ->where('psychologist_id', $psychologist->id)
            ->latest()
            ->take(10)
            ->get();

        return view('doctor-dashboard', compact('stats', 'recent_appointments'));
    }

    public function profile()
    {
        $psychologist = Auth::user()->psychologist;
        return view('doctor-profile', compact('psychologist'));
    }

    public function updateProfile(Request $request)
    {
        $psychologist = Auth::user()->psychologist;

        $request->validate([
            'specialization' => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'qualification_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'consultation_fee' => $request->consultation_fee,
            'bio' => $request->bio,
        ];

        if ($request->hasFile('qualification_files')) {
            $qualificationFiles = $psychologist->qualifications ?? [];
            foreach ($request->file('qualification_files') as $file) {
                $path = $file->store('qualifications', 'public');
                $qualificationFiles[] = $path;
            }
            $data['qualifications'] = $qualificationFiles;
        }

        $psychologist->update($data);

        return redirect()->back()->withSuccess('Profile updated successfully.');
    }

    public function myPatients()
    {
        $psychologist = Auth::user()->psychologist;
        
        if (!$psychologist) {
            return redirect()->route('psychologist.profile.create');
        }

        // Get unique patients for this psychologist
        $patientIds = Appointment::where('psychologist_id', $psychologist->id)
            ->distinct()
            ->pluck('patient_id');

        $patients = \App\Models\Patient::with('user')
            ->whereIn('id', $patientIds)
            ->get()
            ->map(function($patient) use ($psychologist) {
                $lastAppointment = Appointment::where('patient_id', $patient->id)
                    ->where('psychologist_id', $psychologist->id)
                    ->where('status', 'completed')
                    ->latest()
                    ->first();
                
                $patient->last_appointment = $lastAppointment;
                return $patient;
            });

        return view('my-patients', compact('patients', 'psychologist'));
    }
}
