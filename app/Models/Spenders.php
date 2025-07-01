<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spenders extends Model
{
    use HasFactory;

    protected $fillable = [
        'creators_id',
        'name',
        'username',
        'total_spent_gross',
        'vat',
        'platform_fee',
        'total_spent_net',
    ];

    /**
     * Get the chargebacks associated with the spender.
     */
    public function chargebacks()
    {
        return $this->hasMany(Chargeback::class);
    }

    /**
     * Get the transactions associated with the spender.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'spender_id');
    }
}
