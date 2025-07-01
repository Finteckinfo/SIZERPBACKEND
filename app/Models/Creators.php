<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transactions;
use App\Models\DailyGoal;

class Creators extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile',
        'email',
        'password',
        'creator_type',
        'on_board_date',
        'off_board_date',
        'google_sheet_name',
        'google_sheet_name_new_template',
        'account_group',
        'on_platform',
        'on_platform_date',
        'off_platform_date',
        'is_active',
        'archived',
        'content_type',
        'last_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'on_board_date' => 'date',
        'off_board_date' => 'date',
        'on_platform' => 'boolean',
        'on_platform_date' => 'date',
        'off_platform_date' => 'date',
        'is_active' => 'boolean',
        'archived' => 'boolean',
        'last_active' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * A creator has many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'creator_id', 'id');
    }

    /**
     * Accessor to get the first letter of the creator's name as initials.
     *
     */
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->google_sheet_name, 0, 1));
    }

    /**
     * Get the daily goals for the creator.
     */
    public function dailyGoals()
    {
        return $this->hasMany(DailyGoal::class, 'creator_id', 'id');
    }

    /**
     * Get the latest daily goals for the creator.
     */
    public function latestDailyGoals()
    {
        return $this->hasMany(DailyGoal::class)->orderBy('created_at', 'desc');
    }
}
