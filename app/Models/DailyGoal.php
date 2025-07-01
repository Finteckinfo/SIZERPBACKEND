<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'month',
        'shift_goal_date_range',
        'shift_goal',
        'percentage_increase',
        'number_of_shifts',
        'daily_goal',
    ];

  
    protected $casts = [
        'shift_goal' => 'float',
        'percentage_increase' => 'float',
        'number_of_shifts' => 'integer',
        'daily_goal' => 'float',
    ];

    /**
     * Get the creator that owns the daily goal.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }
}
