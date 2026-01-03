<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\PsychologistAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PsychologistAvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:psychologist']);
    }

    public function index()
    {
        $psychologist = Auth::user()->psychologist;
        $availabilities = PsychologistAvailability::where('psychologist_id', $psychologist->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('available-timings', compact('availabilities'));
    }

    public function store(Request $request)
    {
        $psychologist = Auth::user()->psychologist;

        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
        ]);

        PsychologistAvailability::create([
            'psychologist_id' => $psychologist->id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => $request->is_available ?? true,
        ]);

        return redirect()->back()->withSuccess('Availability added successfully.');
    }

    public function update(Request $request, PsychologistAvailability $availability)
    {
        $psychologist = Auth::user()->psychologist;

        if ($availability->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
        ]);

        $availability->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => $request->is_available ?? $availability->is_available,
        ]);

        return redirect()->back()->withSuccess('Availability updated successfully.');
    }

    public function destroy(PsychologistAvailability $availability)
    {
        $psychologist = Auth::user()->psychologist;

        if ($availability->psychologist_id !== $psychologist->id) {
            abort(403, 'Unauthorized');
        }

        $availability->delete();

        return redirect()->back()->withSuccess('Availability deleted successfully.');
    }
}
