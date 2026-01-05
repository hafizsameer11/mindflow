<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Psychologist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('doctor-signup');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Normalize email (trim whitespace)
        $email = trim($request->email);
        
        // Try exact match first (most databases are case-insensitive by default)
        $user = User::where('email', $email)->first();
        
        // If not found, try case-insensitive search
        if (!$user) {
            $user = User::whereRaw('LOWER(TRIM(email)) = LOWER(?)', [$email])->first();
        }
        
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'No account found with this email address.'])->withInput($request->only('email'));
        }
        
        // Use the actual email from database for Auth::attempt
        $credentials = [
            'email' => $user->email,
            'password' => $request->password
        ];
        
        if (!$user->isPsychologist()) {
            return redirect()->back()->withErrors(['email' => 'This account is not registered as a psychologist.'])->withInput($request->only('email'));
        }
        
        if (Auth::attempt($credentials)) {
            return redirect()->intended('psychologist/dashboard')->withSuccess('Signed in');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid password. Please check your password and try again.'])->withInput($request->only('email'));
    }

    public function showRegistrationForm()
    {
        return view('doctor-register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:50',
            'consultation_fee' => 'required|numeric|min:0|max:10000',
            'bio' => 'nullable|string|max:2000',
            'qualification_files' => 'required|array|min:1',
            'qualification_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'qualification_files.required' => 'Please upload at least one certification file.',
            'qualification_files.min' => 'Please upload at least one certification file.',
            'qualification_files.*.required' => 'Each certification file is required.',
            'qualification_files.*.mimes' => 'Certification files must be PDF, JPG, JPEG, or PNG format.',
            'qualification_files.*.max' => 'Each certification file must not exceed 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // The 'hashed' cast in User model will automatically hash this
            'role' => 'psychologist',
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'status' => 'active',
        ]);

        // Handle qualification file uploads
        $qualificationFiles = [];
        if ($request->hasFile('qualification_files')) {
            foreach ($request->file('qualification_files') as $file) {
                $path = $file->store('qualifications', 'public');
                $qualificationFiles[] = $path;
            }
        }

        Psychologist::create([
            'user_id' => $user->id,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'consultation_fee' => $request->consultation_fee,
            'bio' => $request->bio,
            'qualifications' => $qualificationFiles,
            'verification_status' => 'pending',
        ]);

        Auth::login($user);

        return redirect()->route('psychologist.dashboard')->withSuccess('Registration successful! Your account is pending verification.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
