<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function dashboard()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('patient.profile.create');
        }

        $stats = [
            'total_appointments' => Appointment::where('patient_id', $patient->id)->count(),
            'upcoming_appointments' => Appointment::where('patient_id', $patient->id)
                ->where('status', 'confirmed')
                ->where('appointment_date', '>=', today())
                ->count(),
            'completed_appointments' => Appointment::where('patient_id', $patient->id)
                ->where('status', 'completed')
                ->count(),
        ];

        $recent_appointments = Appointment::with('psychologist.user')
            ->where('patient_id', $patient->id)
            ->latest()
            ->take(10)
            ->get();

        return view('patient-dashboard', compact('stats', 'recent_appointments'));
    }

    public function profile()
    {
        $patient = Auth::user()->patient;
        return view('patient-profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patient = Auth::user()->patient;

        $request->validate([
            'medical_history' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
        ]);

        $patient->update([
            'medical_history' => $request->medical_history,
            'emergency_contact' => $request->emergency_contact,
        ]);

        // Update user info
        Auth::user()->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        return redirect()->back()->withSuccess('Profile updated successfully.');
    }
}
