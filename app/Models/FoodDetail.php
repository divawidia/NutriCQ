<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDetail extends Model
{
    use HasFactory;

    protected $table = 'food_diary_details';
    protected $with = ['diary', 'food'];
    protected $guarded = [];

    public function diary(){
        return $this->belongsTo(FoodDiary::class, 'food_diary_id');
    }

    public function food(){
        return $this->belongsTo(Food::class, 'food_id');
    }
}
