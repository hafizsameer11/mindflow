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
        
        // Load availabilities grouped by day
        $availabilities = PsychologistAvailability::where('psychologist_id', $psychologist->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('available-timings', compact('availabilities', 'psychologist'));
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

    public function bulkStore(Request $request)
    {
        $psychologist = Auth::user()->psychologist;

        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'time_slots' => 'required|array|min:1',
            'time_slots.*.start_time' => 'required|date_format:H:i',
            'time_slots.*.end_time' => 'required|date_format:H:i',
        ]);

        // Validate each slot's end time is after start time
        foreach ($request->time_slots as $index => $slot) {
            if ($slot['end_time'] <= $slot['start_time']) {
                return redirect()->back()->withErrors("Time slot #" . ($index + 1) . ": End time must be after start time.");
            }
        }

        $created = 0;
        $skipped = 0;
        foreach ($request->time_slots as $slot) {
            // Check for overlapping slots
            $overlap = PsychologistAvailability::where('psychologist_id', $psychologist->id)
                ->where('day_of_week', $request->day_of_week)
                ->where(function($query) use ($slot) {
                    $query->where(function($q) use ($slot) {
                        // New slot starts within existing slot
                        $q->where('start_time', '<=', $slot['start_time'])
                          ->where('end_time', '>', $slot['start_time']);
                    })->orWhere(function($q) use ($slot) {
                        // New slot ends within existing slot
                        $q->where('start_time', '<', $slot['end_time'])
                          ->where('end_time', '>=', $slot['end_time']);
                    })->orWhere(function($q) use ($slot) {
                        // New slot completely contains existing slot
                        $q->where('start_time', '>=', $slot['start_time'])
                          ->where('end_time', '<=', $slot['end_time']);
                    })->orWhere(function($q) use ($slot) {
                        // Existing slot completely contains new slot
                        $q->where('start_time', '<=', $slot['start_time'])
                          ->where('end_time', '>=', $slot['end_time']);
                    });
                })
                ->exists();

            if (!$overlap) {
                PsychologistAvailability::create([
                    'psychologist_id' => $psychologist->id,
                    'day_of_week' => $request->day_of_week,
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'is_available' => true,
                ]);
                $created++;
            } else {
                $skipped++;
            }
        }

        if ($created > 0) {
            $message = "{$created} time slot(s) added successfully.";
            if ($skipped > 0) {
                $message .= " {$skipped} slot(s) were skipped due to overlaps.";
            }
            return redirect()->back()->withSuccess($message);
        }

        return redirect()->back()->withErrors('No slots were added. All slots overlap with existing ones.');
    }

    public function copyDay(Request $request)
    {
        $psychologist = Auth::user()->psychologist;

        $request->validate([
            'from_day' => 'required|integer|between:0,6',
            'to_days' => 'required|array|min:1',
            'to_days.*' => 'integer|between:0,6',
        ]);

        // Get source day availabilities
        $sourceAvailabilities = PsychologistAvailability::where('psychologist_id', $psychologist->id)
            ->where('day_of_week', $request->from_day)
            ->get();

        if ($sourceAvailabilities->isEmpty()) {
            return redirect()->back()->withErrors('No availability found for the source day.');
        }

        $copied = 0;
        foreach ($request->to_days as $toDay) {
            // Delete existing slots for target day if requested
            if ($request->has('replace_existing')) {
                PsychologistAvailability::where('psychologist_id', $psychologist->id)
                    ->where('day_of_week', $toDay)
                    ->delete();
            }

            // Copy slots
            foreach ($sourceAvailabilities as $source) {
                // Check for overlap
                $overlap = PsychologistAvailability::where('psychologist_id', $psychologist->id)
                    ->where('day_of_week', $toDay)
                    ->where(function($query) use ($source) {
                        $query->whereBetween('start_time', [$source->start_time, $source->end_time])
                              ->orWhereBetween('end_time', [$source->start_time, $source->end_time])
                              ->orWhere(function($q) use ($source) {
                                  $q->where('start_time', '<=', $source->start_time)
                                    ->where('end_time', '>=', $source->end_time);
                              });
                    })
                    ->exists();

                if (!$overlap) {
                    PsychologistAvailability::create([
                        'psychologist_id' => $psychologist->id,
                        'day_of_week' => $toDay,
                        'start_time' => $source->start_time,
                        'end_time' => $source->end_time,
                        'is_available' => $source->is_available,
                    ]);
                    $copied++;
                }
            }
        }

        return redirect()->back()->withSuccess("Schedule copied successfully. {$copied} slot(s) added.");
    }

    public function deleteDay(Request $request)
    {
        $psychologist = Auth::user()->psychologist;

        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
        ]);

        $deleted = PsychologistAvailability::where('psychologist_id', $psychologist->id)
            ->where('day_of_week', $request->day_of_week)
            ->delete();

        return redirect()->back()->withSuccess("All availability for the selected day has been deleted.");
    }
}
