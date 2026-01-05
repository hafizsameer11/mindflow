<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Normalize email (trim whitespace)
        $email = trim($request->email);
        
        // Find user by email (try multiple methods)
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Try case-insensitive
            $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();
        }
        
        if (!$user) {
            // Try with trim in database
            $user = User::whereRaw('TRIM(email) = ?', [$email])->first();
        }
        
        if (!$user) {
            // Last attempt: case-insensitive with trim
            $user = User::whereRaw('LOWER(TRIM(email)) = LOWER(?)', [$email])->first();
        }
        
        if (!$user) {
            // Debug: Let's see all users to help diagnose
            $allUsers = User::select('id', 'email', 'role')->get();
            \Log::info('Login attempt failed - User not found', [
                'attempted_email' => $email,
                'available_users' => $allUsers->pluck('email')->toArray()
            ]);
            
            return redirect()->back()->withErrors(['email' => 'No account found with this email address. Please check your email and try again.'])->withInput($request->only('email'));
        }
        
        if (!$user->isPatient()) {
            return redirect()->back()->withErrors(['email' => 'This account is not registered as a patient.'])->withInput($request->only('email'));
        }
        
        // Use the actual email from database for Auth::attempt
        $credentials = [
            'email' => $user->email,
            'password' => $request->password
        ];
        
        // Try Auth::attempt first
        if (Auth::attempt($credentials)) {
            return redirect()->intended('patient/dashboard')->withSuccess('Signed in');
        }
        
        // If Auth::attempt fails, manually check password (in case of double-hashing issue)
        $passwordCheck = Hash::check($request->password, $user->password);
        
        // Debug logging
        \Log::info('Login attempt - Auth failed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_from_db' => $user->email,
            'password_check' => $passwordCheck,
            'password_hash_length' => strlen($user->password),
            'password_hash_prefix' => substr($user->password, 0, 10)
        ]);

        if ($passwordCheck) {
            // Password is correct but Auth::attempt failed - manually log in
            Auth::login($user);
            return redirect()->intended('patient/dashboard')->withSuccess('Signed in');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid password. The password you entered is incorrect. Please try again or reset your password.'])->withInput($request->only('email'));
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // The 'hashed' cast in User model will automatically hash this
            'role' => 'patient',
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'status' => 'active',
        ]);

        Patient::create([
            'user_id' => $user->id,
            'medical_history' => $request->medical_history,
            'emergency_contact' => $request->emergency_contact,
        ]);

        Auth::login($user);

        return redirect()->route('patient.dashboard')->withSuccess('Registration successful!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
