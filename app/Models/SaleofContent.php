<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SaleofContent extends Model{

    use HasFactory;
    protected $table = 'saleofcontent';

    protected $fillable = [
        'creators_id',
        'fan_id',
        'content_purchased',
        'amount_paid',
        'sale_status',
        'fulfilment_status'
    ];

    public function creator()
    {
        return $this->belongsTo(Creators::class);
    }

    public function fan()
    {
        return $this->belongsTo(Spenders::class);
    }
}

