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

    public function calculateFoodForFoodDiaryDetail($foodDiaryId, $foodId, $calculateFood, $servingSize)
    {
        $foodCalculated = array(
            'food_diary_id' => $foodDiaryId,
            'food_id' => $foodId,
            'air' => $calculateFood[0]['air'],
            'energi' => $calculateFood[0]['energi'],
            'protein' => $calculateFood[0]['protein'],
            'lemak' => $calculateFood[0]['lemak'],
            'karbohidrat' => $calculateFood[0]['karbohidrat'],
            'serat' => $calculateFood[0]['serat'],
            'abu' => $calculateFood[0]['abu'],
            'kalsium' => $calculateFood[0]['kalsium'],
            'fosfor' => $calculateFood[0]['fosfor'],
            'besi' => $calculateFood[0]['besi'],
            'natrium' => $calculateFood[0]['natrium'],
            'kalium' => $calculateFood[0]['kalium'],
            'tembaga' => $calculateFood[0]['tembaga'],
            'seng' => $calculateFood[0]['seng'],
            'retinol' => $calculateFood[0]['retinol'],
            'b_karoten' => $calculateFood[0]['b_karoten'],
            'karoten_total' => $calculateFood[0]['karoten_total'],
            'thiamin' => $calculateFood[0]['thiamin'],
            'riboflamin' => $calculateFood[0]['riboflamin'],
            'niasin' => $calculateFood[0]['niasin'],
            'vitamin_c' => $calculateFood[0]['vitamin_c'],
            'takaran_saji' => $servingSize
        );
        return $foodCalculated;
    }

    public function foodDiary(): BelongsTo
    {
        return $this->belongsTo(FoodDiary::class);
    }
    public function foods(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
