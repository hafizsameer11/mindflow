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

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->isPsychologist()) {
                return redirect()->intended('psychologist/dashboard')->withSuccess('Signed in');
            }
            
            Auth::logout();
            return redirect()->back()->withErrors('Invalid credentials for psychologist access.');
        }

        return redirect()->back()->withErrors('These credentials do not match our records.');
    }

    public function showRegistrationForm()
    {
        return view('doctor-register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string',
            'specialization' => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'qualifications' => 'nullable|array',
            'qualification_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'psychologist',
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
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
