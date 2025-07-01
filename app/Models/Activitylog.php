<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activitylog extends Model
{
    use HasFactory;

    protected $fillable = [
        'time', 'type', 'event',
        'performed_on', 'performed_by', 'details'
    ];

    protected $casts = [
        'details' => 'array',
        'time' => 'datetime'
    ];

}
