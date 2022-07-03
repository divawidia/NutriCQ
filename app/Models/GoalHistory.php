<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalHistory extends Model
{
    use HasFactory;

    protected $table = 'goal_histories';

    protected $guarded = [];
}
