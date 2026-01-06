<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Feedback;
use App\Models\Payment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

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

        // Get upcoming/active sessions (confirmed appointments that can be joined)
        $upcoming_sessions = Appointment::with(['psychologist.user', 'payment'])
            ->where('patient_id', $patient->id)
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

        // Get feedback statistics
        $feedbackStats = [
            'total' => Feedback::where('patient_id', $patient->id)->count(),
            'average_rating' => Feedback::where('patient_id', $patient->id)->avg('rating') 
                ? round(Feedback::where('patient_id', $patient->id)->avg('rating'), 1) 
                : 0,
            'rating_5' => Feedback::where('patient_id', $patient->id)->where('rating', 5)->count(),
            'rating_4' => Feedback::where('patient_id', $patient->id)->where('rating', 4)->count(),
            'rating_3' => Feedback::where('patient_id', $patient->id)->where('rating', 3)->count(),
            'rating_2' => Feedback::where('patient_id', $patient->id)->where('rating', 2)->count(),
            'rating_1' => Feedback::where('patient_id', $patient->id)->where('rating', 1)->count(),
        ];

        // Get recent feedback
        $recent_feedback = Feedback::with(['psychologist.user', 'appointment'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->take(5)
            ->get();

        // Get completed appointments without feedback (pending feedback)
        $pending_feedback = Appointment::with('psychologist.user')
            ->where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->whereDoesntHave('feedback')
            ->latest()
            ->take(5)
            ->get();

        return view('patient-dashboard', compact(
            'stats', 
            'recent_appointments', 
            'upcoming_sessions',
            'feedbackStats', 
            'recent_feedback', 
            'pending_feedback'
        ));
    }

    public function notifications()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('patient.profile.create');
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

        return view('patient.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function history()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('patient.profile.create');
        }

        // Get all history data (not limited to 10)
        $history_appointments = Appointment::with(['psychologist.user', 'payment', 'prescription', 'feedback'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->paginate(15);

        $history_payments = Payment::with(['appointment.psychologist.user'])
            ->whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->latest()
            ->paginate(15);

        $history_prescriptions = Prescription::with(['appointment.psychologist.user'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(15);

        // Calculate treatment progress
        $treatment_progress = [
            'total_sessions' => Appointment::where('patient_id', $patient->id)
                ->where('status', 'completed')
                ->count(),
            'total_prescriptions' => Prescription::where('patient_id', $patient->id)->count(),
            'total_payments' => Payment::whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })->where('status', 'verified')->count(),
            'total_spent' => Payment::whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })->where('status', 'verified')->sum('amount'),
        ];

        return view('patient.history.index', compact(
            'history_appointments',
            'history_payments',
            'history_prescriptions',
            'treatment_progress'
        ));
    }

    public function profile()
    {
        $patient = Auth::user()->patient;
        return view('patient-profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patient = Auth::user()->patient;

        // Create patient if doesn't exist
        if (!$patient) {
            $patient = Patient::create([
                'user_id' => Auth::id(),
            ]);
        }

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'medical_history' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Update patient info if provided
        $patientData = [];
        if ($request->has('medical_history')) {
            $patientData['medical_history'] = $request->medical_history;
        }
        if ($request->has('emergency_contact')) {
            $patientData['emergency_contact'] = $request->emergency_contact;
        }
        if (!empty($patientData)) {
            $patient->update($patientData);
        }

        // Handle profile image upload
        if ($request->has('remove_image') && $request->remove_image == '1') {
            // Remove existing image
            if (Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image)) {
                Storage::disk('public')->delete(Auth::user()->profile_image);
            }
            $userData['profile_image'] = null;
        } elseif ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if (Auth::user()->profile_image && Storage::disk('public')->exists(Auth::user()->profile_image)) {
                Storage::disk('public')->delete(Auth::user()->profile_image);
            }
            // Store new image
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $userData['profile_image'] = $path;
        }

        // Update user info if provided
        if (!isset($userData)) {
            $userData = [];
        }
        if ($request->has('phone')) {
            $userData['phone'] = $request->phone;
        }
        if ($request->has('address')) {
            $userData['address'] = $request->address;
        }
        if ($request->has('date_of_birth')) {
            $userData['date_of_birth'] = $request->date_of_birth;
        }
        if ($request->has('gender')) {
            $userData['gender'] = $request->gender;
        }
        if (!empty($userData)) {
            Auth::user()->update($userData);
        }

        return redirect()->back()->withSuccess('Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->withSuccess('Password updated successfully.');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        // Store preferences as JSON in user settings or create a preferences column
        // For now, we'll store in a JSON column or use a separate table
        // This is a simplified version - you may want to create a user_preferences table
        
        $preferences = [
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
            'appointment_reminders' => $request->has('appointment_reminders'),
            'payment_notifications' => $request->has('payment_notifications'),
            'marketing_emails' => $request->has('marketing_emails'),
            'profile_visibility' => $request->has('profile_visibility'),
            'data_sharing' => $request->has('data_sharing'),
        ];

        // Store preferences - you can add a 'preferences' JSON column to users table
        // For now, we'll just return success
        // $user->update(['preferences' => json_encode($preferences)]);

        return redirect()->back()->withSuccess('Preferences updated successfully.');
    }
}
