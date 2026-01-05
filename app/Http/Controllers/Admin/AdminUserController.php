<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = User::with(['psychologist', 'patient']);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->get();

        // Get last login info from sessions table
        $users->transform(function($user) {
            $lastSession = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->first();
            
            $user->last_login = $lastSession ? Carbon::createFromTimestamp($lastSession->last_activity) : null;
            return $user;
        });

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,psychologist,patient',
            'phone' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // The 'hashed' cast in User model will automatically hash this
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        // If creating psychologist or patient, create related profile
        if ($request->role === 'psychologist') {
            \App\Models\Psychologist::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization ?? 'General',
                'experience_years' => $request->experience_years ?? 0,
                'consultation_fee' => $request->consultation_fee ?? 0,
                'verification_status' => 'pending',
            ]);
        } elseif ($request->role === 'patient') {
            \App\Models\Patient::create([
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('admin.users.index')->withSuccess('User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,psychologist,patient',
            'phone' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password; // The 'hashed' cast in User model will automatically hash this
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->withSuccess('User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->back()->withErrors('You cannot delete your own account.');
        }

        // Delete related records
        if ($user->psychologist) {
            $user->psychologist->delete();
        }
        if ($user->patient) {
            $user->patient->delete();
        }

        $user->delete();
        return redirect()->route('admin.users.index')->withSuccess('User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Prevent deactivating yourself
        if ($user->id === Auth::id() && $user->status === 'active') {
            return redirect()->back()->withErrors('You cannot deactivate your own account.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $user->status === 'active' ? 'activated' : 'deactivated';
        return redirect()->back()->withSuccess("User account {$status} successfully.");
    }
}
