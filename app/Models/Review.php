<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $table = 'reviews';
    protected $fillable = [
        'user_id',
        'doctor_id',
        'comment',
        'total_rating'
    ];

    public function userReview()
    {
        return $this->belongsTo(User::class);
    }
}
