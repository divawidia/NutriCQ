<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    public function detail()
    {
        return $this->hasMany(Food_Detail::class, 'food_id');
    }
}
