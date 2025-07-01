<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chargebacks extends Model
{
    protected $fillable = [
        'spender_id',
        'transaction_id',
        'amount',
        'currency',
        'reason',
        'status',
        'payment_gateway',
        'gateway_reference',
        'submitted_at',
        'resolved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function spender()
    {
        return $this->belongsTo(Spender::class);
    }
}
