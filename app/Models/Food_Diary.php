<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_Diary extends Model
{
    use HasFactory;

    protected $table = 'food_diaries';

    public function detail()
    {
        return $this->hasMany(Food_Detail::class, 'food_diary_id');
    }
}
