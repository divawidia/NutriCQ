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

    public function foodDiary(): BelongsTo
    {
        return $this->belongsTo(FoodDiary::class);
    }
}
