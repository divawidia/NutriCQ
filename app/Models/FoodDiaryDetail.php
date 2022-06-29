<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodDiaryDetail extends Model
{
    use HasFactory;

    protected $table = 'food_diary_details';

    protected $guarded = [];

    protected $with = ['foods'];

    public function foodDiary(): BelongsTo
    {
        return $this->belongsTo(FoodDiary::class);
    }
    public function foods(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'food_id', 'id');
    }
}
