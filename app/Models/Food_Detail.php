<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_Detail extends Model
{
    use HasFactory;

    protected $table = 'food_diary_details';
    protected $with = ['diary', 'food'];

    // public function scopeCek($query, $filter)
    // {
    //     return $query->where('food_diary_id', $filter);
    // }

    public function diary(){
        return $this->belongsTo(Food_Diary::class, 'food_diary_id');
    }

    public function food(){
        return $this->belongsTo(Food::class, 'food_id');
    }
    
    // public function food_diary(){
    //     return $this->belongsTo(Food_Diary::class, 'food_diary_id');
    // }

    // public function food(){
    //     return $this->belongsTo(Food::class, 'food_id');
    // }
}
