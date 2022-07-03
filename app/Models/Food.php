<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $guarded = [];

    protected $with = ['foodCategory'];

    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class, 'category_id', 'id');
    }

    public function foodDiaryDetails(): HasMany
    {
        return $this->hasMany(FoodDiaryDetail::class, 'food_id', 'id');
    }

}
