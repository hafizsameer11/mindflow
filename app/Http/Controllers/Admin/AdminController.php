<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Psychologist;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_psychologists' => Psychologist::count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
            'pending_verifications' => Psychologist::where('verification_status', 'pending')->count(),
            'pending_payments' => Payment::where('status', 'pending_verification')->count(),
        ];

        $recent_appointments = Appointment::with(['patient.user', 'psychologist.user'])
            ->latest()
            ->take(10)
            ->get();

        // Get top psychologists with their earnings and reviews
        $top_psychologists = Psychologist::with('user')
            ->take(5)
            ->get()
            ->map(function($psychologist) {
                // Calculate total earnings from verified payments
                $earnings = Payment::whereHas('appointment', function($q) use ($psychologist) {
                    $q->where('psychologist_id', $psychologist->id);
                })
                ->where('status', 'verified')
                ->sum('amount');
                
                // Calculate average rating
                $avgRating = \App\Models\Feedback::where('psychologist_id', $psychologist->id)
                    ->avg('rating') ?? 0;
                
                $psychologist->total_earnings = $earnings;
                $psychologist->average_rating = round($avgRating, 1);
                return $psychologist;
            })
            ->sortByDesc('total_earnings')
            ->values();

        // Get recent patients with their last visit and total paid
        $recent_patients = Patient::with('user')
            ->take(5)
            ->get()
            ->map(function($patient) {
                $lastAppointment = Appointment::where('patient_id', $patient->id)
                    ->latest()
                    ->first();
                
                $totalPaid = Payment::whereHas('appointment', function($q) use ($patient) {
                    $q->where('patient_id', $patient->id);
                })
                ->where('status', 'verified')
                ->sum('amount');
                
                $patient->last_visit = $lastAppointment ? $lastAppointment->appointment_date : null;
                $patient->total_paid = $totalPaid;
                return $patient;
            });

        // Get pending refund requests
        $refund_requests = Payment::with(['appointment.patient.user', 'appointment.psychologist.user', 'refundRequester'])
            ->where('refund_status', 'requested')
            ->latest('refund_requested_at')
            ->take(10)
            ->get();

        $refund_stats = [
            'pending' => Payment::where('refund_status', 'requested')->count(),
            'approved' => Payment::where('refund_status', 'approved')->count(),
            'processed' => Payment::where('refund_status', 'processed')->count(),
            'total_amount' => Payment::where('refund_status', 'requested')->sum('refund_amount'),
        ];

        return view('admin.index_admin', compact('stats', 'recent_appointments', 'top_psychologists', 'recent_patients', 'refund_requests', 'refund_stats'));
    }

    public function patientList()
    {
        $patients = Patient::with('user')->get();
        
        // Add additional data to each patient
        $patients->transform(function($patient) {
            // Get last appointment date
            $lastAppointment = Appointment::where('patient_id', $patient->id)
                ->where('status', 'completed')
                ->latest()
                ->first();
            
            // Calculate total paid
            $totalPaid = Payment::whereHas('appointment', function($q) use ($patient) {
                $q->where('patient_id', $patient->id);
            })
            ->where('status', 'verified')
            ->sum('amount');
            
            $patient->last_visit_date = $lastAppointment ? $lastAppointment->appointment_date : null;
            $patient->total_paid = $totalPaid;
            return $patient;
        });

        return view('admin.patient-list', compact('patients'));
    }

    public function specialities()
    {
        // Get unique specializations from psychologists
        $specialities = \App\Models\Psychologist::select('specialization')
            ->distinct()
            ->whereNotNull('specialization')
            ->orderBy('specialization')
            ->get()
            ->map(function($item, $index) {
                return [
                    'id' => $index + 1,
                    'code' => '#SP' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'specialization' => $item->specialization,
                    'count' => \App\Models\Psychologist::where('specialization', $item->specialization)->count(),
                ];
            });

        return view('admin.specialities', compact('specialities'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'remove_profile_image' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ];

        // Handle profile image removal
        if ($request->has('remove_profile_image') && $request->remove_profile_image) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = null;
        } elseif ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // Store new image
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $data['profile_image'] = $path;
        }

        $user->update($data);

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

    public function settings()
    {
        return view('admin.settings');
    }
}
