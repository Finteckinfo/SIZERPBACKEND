<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'spender_id',       // User who is spending
        'creator_id',       // Creator receiving the payment
        'transaction_id',   // Unique transaction identifier (external)
        'amount',           // Total amount paid
        'payment_type',     // e.g., subscription, tip
        'currency',         // Currency code, e.g. USD
        'platform_fee',     // Fee charged by Rockitfans platform
        'payment_method',   // e.g., card, PayPal
        'payment_gateway',  // e.g., Stripe, PayPal
        'items',            // String describing what is paid for - e.g Payment for video
        'type',             // Possibly more specific type (optional)
        'status',           // e.g., completed, pending, refunded
        'processed_at',     // Timestamp of processing
        'refunded_at',      // Timestamp of refund (nullable)
    ];

    protected $casts = [
        'amount' => 'float',
        'platform_fee' => 'float',
        'items' => 'array',          // JSON cast
        'processed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(Creators::class, 'creator_id');
    }

    public function spender()
    {
        return $this->belongsTo(Spenders::class, 'spender_id');
    }
}
