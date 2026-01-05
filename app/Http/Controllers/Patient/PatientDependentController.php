<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Dependent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatientDependentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function index()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            abort(404, 'Patient profile not found');
        }

        $dependents = Dependent::where('patient_id', $patient->id)
            ->latest()
            ->get();

        return view('dependent', compact('dependents'));
    }

    public function store(Request $request)
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dependent')->with('error', 'Patient profile not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|in:Spouse,Child,Parent,Sibling,Other',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:4096',
        ]);

        $data = [
            'patient_id' => $patient->id,
            'name' => $validated['name'],
            'relationship' => $validated['relationship'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('dependent-images', $imageName, 'public');
            $data['profile_image'] = $imagePath;
        }

        Dependent::create($data);

        return redirect()->route('dependent')->with('success', 'Dependant added successfully.');
    }

    public function update(Request $request, Dependent $dependent)
    {
        $patient = Auth::user()->patient;

        if (!$patient || $dependent->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|in:Spouse,Child,Parent,Sibling,Other',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:4096',
        ]);

        $data = [
            'name' => $request->name,
            'relationship' => $request->relationship,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'phone' => $request->phone,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($dependent->profile_image) {
                Storage::disk('public')->delete($dependent->profile_image);
            }

            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('dependent-images', $imageName, 'public');
            $data['profile_image'] = $imagePath;
        }

        $dependent->update($data);

        return redirect()->back()->with('success', 'Dependant updated successfully.');
    }

    public function destroy(Dependent $dependent)
    {
        $patient = Auth::user()->patient;

        if (!$patient || $dependent->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        // Delete profile image if exists
        if ($dependent->profile_image) {
            Storage::disk('public')->delete($dependent->profile_image);
        }

        $dependent->delete();

        return redirect()->back()->with('success', 'Dependant deleted successfully.');
    }

    public function toggleStatus(Dependent $dependent)
    {
        $patient = Auth::user()->patient;

        if (!$patient || $dependent->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        $dependent->update([
            'is_active' => !$dependent->is_active
        ]);

        return redirect()->back()->with('success', 'Dependant status updated successfully.');
    }
}
