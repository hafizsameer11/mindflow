<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Psychologist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                ->whereDate('appointment_date', today())
                ->count(),
            'upcoming_appointments' => Appointment::where('psychologist_id', $psychologist->id)
                ->where('status', 'confirmed')
                ->whereDate('appointment_date', '>=', today())
                ->count(),
            'completed_appointments' => Appointment::where('psychologist_id', $psychologist->id)
                ->where('status', 'completed')
                ->count(),
        ];

        $recent_appointments = Appointment::with(['patient.user', 'psychologist'])
            ->where('psychologist_id', $psychologist->id)
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->take(10)
            ->get();

        // Get upcoming appointment (next confirmed appointment)
        $upcoming_appointment = Appointment::with(['patient.user', 'psychologist'])
            ->where('psychologist_id', $psychologist->id)
            ->where('status', 'confirmed')
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->first();

        // Get recent patients (last 2 unique patients with completed appointments)
        $recent_patients = Appointment::with(['patient.user'])
            ->where('psychologist_id', $psychologist->id)
            ->where('status', 'completed')
            ->select('patient_id', DB::raw('MAX(appointment_date) as last_appointment_date'))
            ->groupBy('patient_id')
            ->orderBy('last_appointment_date', 'desc')
            ->take(2)
            ->get()
            ->map(function($appointment) use ($psychologist) {
                $patient = $appointment->patient;
                $lastAppointment = Appointment::where('patient_id', $patient->id)
                    ->where('psychologist_id', $psychologist->id)
                    ->where('status', 'completed')
                    ->latest('appointment_date')
                    ->first();
                return [
                    'patient' => $patient,
                    'last_appointment' => $lastAppointment,
                ];
            });

        // Get recent invoices (payments)
        $recent_invoices = Payment::with(['appointment.patient.user'])
            ->whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Weekly overview data (last 7 days)
        $weeklyRevenue = Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->where('status', 'verified')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $weeklyAppointments = Appointment::where('psychologist_id', $psychologist->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Get upcoming/active sessions (confirmed appointments that can be started)
        $upcoming_sessions = Appointment::with(['patient.user', 'payment'])
            ->where('psychologist_id', $psychologist->id)
            ->where('status', 'confirmed')
            ->where('consultation_type', 'video')
            ->where(function($query) {
                $query->whereDate('appointment_date', '>=', today())
                      ->orWhere(function($q) {
                          $q->whereDate('appointment_date', today())
                            ->whereTime('appointment_time', '<=', now()->addHours(2));
                      });
            })
            ->whereHas('payment', function($q) {
                $q->where('status', 'verified');
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->take(5)
            ->get();

        // Get refund requests for this psychologist's appointments
        $refund_requests = Payment::with(['appointment.patient.user', 'refundRequester'])
            ->whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })
            ->where('refund_status', 'requested')
            ->latest('refund_requested_at')
            ->take(10)
            ->get();

        $refund_stats = [
            'pending' => Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })->where('refund_status', 'requested')->count(),
            'approved' => Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })->where('refund_status', 'approved')->count(),
            'processed' => Payment::whereHas('appointment', function($q) use ($psychologist) {
                $q->where('psychologist_id', $psychologist->id);
            })->where('refund_status', 'processed')->count(),
        ];

        // Get recent notifications (announcements)
        $notifications = Auth::user()->notifications()
            ->where('type', 'App\Notifications\AdminAnnouncementNotification')
            ->latest()
            ->take(10)
            ->get();

        // Get unread notification count
        $unreadCount = Auth::user()->unreadNotifications()
            ->where('type', 'App\Notifications\AdminAnnouncementNotification')
            ->count();

        return view('doctor-dashboard', compact(
            'stats', 
            'recent_appointments', 
            'upcoming_appointment',
            'upcoming_sessions',
            'recent_patients',
            'recent_invoices',
            'weeklyRevenue',
            'weeklyAppointments',
            'refund_requests',
            'refund_stats'
        ));
    }

    public function notifications()
    {
        $psychologist = Auth::user()->psychologist;
        
        if (!$psychologist) {
            return redirect()->route('psychologist.profile');
        }

        // Get all notifications (announcements) with pagination
        $notifications = Auth::user()->notifications()
            ->where('type', 'App\Notifications\AdminAnnouncementNotification')
            ->latest()
            ->paginate(20);

        // Get unread notification count
        $unreadCount = Auth::user()->unreadNotifications()
            ->where('type', 'App\Notifications\AdminAnnouncementNotification')
            ->count();

        return view('psychologist.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function profile()
    {
        $psychologist = Auth::user()->psychologist;
        $user = Auth::user();
        
        if (!$psychologist) {
            return redirect()->route('psychologist.register');
        }

        // Load availabilities grouped by day
        $availabilities = $psychologist->availabilities()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('psychologist-profile', compact('psychologist', 'user', 'availabilities'));
    }

    public function updateProfile(Request $request)
    {
        $psychologist = Auth::user()->psychologist;
        $user = Auth::user();

        if (!$psychologist) {
            return redirect()->route('psychologist.register');
        }

        // Determine which section is being updated
        $section = $request->input('section', 'professional');

        if ($section === 'personal') {
            // Update user contact details
            $request->validate([
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'remove_image' => 'nullable|boolean',
            ]);

            $userData = [];
            if ($request->filled('phone')) {
                $userData['phone'] = $request->phone;
            }
            if ($request->filled('address')) {
                $userData['address'] = $request->address;
            }
            if ($request->filled('date_of_birth')) {
                $userData['date_of_birth'] = $request->date_of_birth;
            }
            if ($request->filled('gender')) {
                $userData['gender'] = $request->gender;
            }

            // Handle profile image
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $userData['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
            } elseif ($request->input('remove_image')) {
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $userData['profile_image'] = null;
            }

            $user->update($userData);
            return redirect()->back()->withSuccess('Personal information updated successfully.');

        } elseif ($section === 'professional') {
            // Update professional information
            $request->validate([
                'specialization' => 'required|string|max:255',
                'experience_years' => 'required|integer|min:0|max:100',
                'consultation_fee' => 'required|numeric|min:0',
                'bio' => 'nullable|string|max:2000',
            ]);

            $psychologist->update([
                'specialization' => $request->specialization,
                'experience_years' => $request->experience_years,
                'consultation_fee' => $request->consultation_fee,
                'bio' => $request->bio,
            ]);

            return redirect()->back()->withSuccess('Professional information updated successfully.');

        } elseif ($section === 'qualifications') {
            // Handle qualification files
            if ($request->hasFile('qualification_files')) {
                $request->validate([
                    'qualification_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);

                $qualificationFiles = $psychologist->qualifications ?? [];
                foreach ($request->file('qualification_files') as $file) {
                    $path = $file->store('qualifications', 'public');
                    $qualificationFiles[] = $path;
                }
                $psychologist->update(['qualifications' => $qualificationFiles]);
            }

            // Handle removal of specific qualification
            if ($request->has('remove_qualification')) {
                $index = $request->input('remove_qualification');
                $qualificationFiles = $psychologist->qualifications ?? [];
                
                if (isset($qualificationFiles[$index])) {
                    // Delete the file
                    Storage::disk('public')->delete($qualificationFiles[$index]);
                    // Remove from array
                    unset($qualificationFiles[$index]);
                    // Re-index array
                    $qualificationFiles = array_values($qualificationFiles);
                    $psychologist->update(['qualifications' => $qualificationFiles]);
                }
            }

            return redirect()->back()->withSuccess('Qualifications updated successfully.');
        }

        return redirect()->back()->withError('Invalid section.');
    }

    public function downloadQualification($index)
    {
        $psychologist = Auth::user()->psychologist;
        
        if (!$psychologist || !$psychologist->qualifications) {
            abort(404, 'Qualification not found');
        }

        $qualifications = $psychologist->qualifications;
        
        if (!isset($qualifications[$index])) {
            abort(404, 'Qualification not found');
        }

        $filePath = $qualifications[$index];
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($filePath);
    }

    public function viewQualification($index)
    {
        $psychologist = Auth::user()->psychologist;
        
        if (!$psychologist || !$psychologist->qualifications) {
            abort(404, 'Qualification not found');
        }

        $qualifications = $psychologist->qualifications;
        
        if (!isset($qualifications[$index])) {
            abort(404, 'Qualification not found');
        }

        $filePath = $qualifications[$index];
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
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
