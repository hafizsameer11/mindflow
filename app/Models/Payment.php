<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'amount',
        'receipt_file_path',
        'bank_name',
        'transaction_id',
        'status',
        'uploaded_at',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'dispute_status',
        'dispute_reason',
        'disputed_at',
        'disputed_by',
        'dispute_resolution',
        'dispute_resolved_at',
        'dispute_resolved_by',
        'refund_status',
        'refund_reason',
        'refund_amount',
        'refund_requested_at',
        'refund_requested_by',
        'refund_processed_at',
        'refund_processed_by',
        'refund_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
        'disputed_at' => 'datetime',
        'dispute_resolved_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'refund_processed_at' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function disputer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disputed_by');
    }

    public function disputeResolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispute_resolved_by');
    }

    public function refundRequester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refund_requested_by');
    }

    public function refundProcessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refund_processed_by');
    }
}
