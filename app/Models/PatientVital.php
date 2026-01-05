<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientVital extends Model
{
    protected $fillable = [
        'patient_id',
        'bmi',
        'heart_rate',
        'weight',
        'fbc',
        'blood_pressure',
        'glucose_level',
        'body_temperature',
        'recorded_date',
        'notes',
    ];

    protected $casts = [
        'recorded_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
