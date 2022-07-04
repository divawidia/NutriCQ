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

    public function calculateFood($servingSize, $id)
    {
        $calculateFood = Food::selectRaw(
            'id,
                name,
                sumber,
                air *' . $servingSize . '/100 AS air,
                energi *' . $servingSize . '/100 AS energi,
                protein *' . $servingSize . '/100 AS protein,
                lemak *' . $servingSize . '/100 AS lemak,
                karbohidrat *' . $servingSize . '/100 AS karbohidrat,
                serat *' . $servingSize . '/100 AS serat,
                abu *' . $servingSize . '/100 AS abu,
                kalsium *' . $servingSize . '/100 AS kalsium,
                fosfor *' . $servingSize . '/100 AS fosfor,
                besi *' . $servingSize . '/100 AS besi,
                natrium *' . $servingSize . '/100 AS natrium,
                kalium *' . $servingSize . '/100 AS kalium,
                tembaga *' . $servingSize . '/100 AS tembaga,
                seng *' . $servingSize . '/100 AS seng,
                retinol *' . $servingSize . '/100 AS retinol,
                b_karoten *' . $servingSize . '/100 AS b_karoten,
                karoten_total *' . $servingSize . '/100 AS karoten_total,
                thiamin *' . $servingSize . '/100 AS thiamin,
                riboflamin *' . $servingSize . '/100 AS riboflamin,
                niasin *' . $servingSize . '/100 AS niasin,
                vitamin_c *' . $servingSize . '/100 AS vitamin_c,
                porsi_berat_dapat_dimakan,
                category_id,
                created_at,
                updated_at')
            ->where('id', $id)
            ->get();

        return $calculateFood;
    }

}
