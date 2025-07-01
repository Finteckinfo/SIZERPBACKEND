<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'from' ,
        'to_userid' ,
        'title' ,
        'message' ,
        'budget_allocated' ,
        'task_id' ,
        'workspace_id' ,
        'links',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
