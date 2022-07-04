<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class FoodDiary extends Model
{
    use HasFactory;
    protected $table = 'food_diaries';

    protected $guarded = [];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function foodDiaryDetails(): HasMany
    {
        return $this->hasMany(FoodDiaryDetail::class);
    }

    public function updateWhenFoodAdded($calculateFood)
    {
        $updatedFoodDiary = array(
            'total_air' => DB::raw('total_air + '.$calculateFood[0]['air']),
            'total_energi' => DB::raw('total_energi + '.$calculateFood[0]['energi']),
            'total_protein' => DB::raw('total_protein + '.$calculateFood[0]['protein']),
            'total_lemak' => DB::raw('total_lemak + '.$calculateFood[0]['lemak']),
            'total_karbohidrat' => DB::raw('total_karbohidrat + '.$calculateFood[0]['karbohidrat']),
            'total_serat' => DB::raw('total_serat + '.$calculateFood[0]['serat']),
            'total_abu' => DB::raw('total_abu + '.$calculateFood[0]['abu']),
            'total_kalsium' => DB::raw('total_kalsium + '.$calculateFood[0]['kalsium']),
            'total_fosfor' => DB::raw('total_fosfor + '.$calculateFood[0]['fosfor']),
            'total_besi' => DB::raw('total_besi + '.$calculateFood[0]['besi']),
            'total_natrium' => DB::raw('total_natrium + '.$calculateFood[0]['natrium']),
            'total_kalium' => DB::raw('total_kalium + '.$calculateFood[0]['kalium']),
            'total_tembaga' => DB::raw('total_tembaga + '.$calculateFood[0]['tembaga']),
            'total_seng' => DB::raw('total_seng + '.$calculateFood[0]['seng']),
            'total_retinol' => DB::raw('total_retinol + '.$calculateFood[0]['retinol']),
            'total_b_karoten' => DB::raw('total_b_karoten + '.$calculateFood[0]['b_karoten']),
            'total_karoten_total' => DB::raw('total_karoten_total + '.$calculateFood[0]['karoten_total']),
            'total_thiamin' => DB::raw('total_thiamin + '.$calculateFood[0]['thiamin']),
            'total_riboflamin' => DB::raw('total_riboflamin + '.$calculateFood[0]['riboflamin']),
            'total_niasin' => DB::raw('total_niasin + '.$calculateFood[0]['niasin']),
            'total_vitamin_c' => DB::raw('total_vitamin_c + '.$calculateFood[0]['vitamin_c']),
            'jumlah_makanan' => DB::raw('jumlah_makanan + 1')
        );
        return $updatedFoodDiary;
    }

    public function updateWhenFoodDeleted($foodDiaryDetail)
    {
        $updatedFoodDiary = array(
            'total_air' => DB::raw('total_air - ' . $foodDiaryDetail->air),
            'total_energi' => DB::raw('total_energi - ' . $foodDiaryDetail->energi),
            'total_protein' => DB::raw('total_protein - ' . $foodDiaryDetail->protein),
            'total_lemak' => DB::raw('total_lemak - ' . $foodDiaryDetail->lemak),
            'total_karbohidrat' => DB::raw('total_karbohidrat - ' . $foodDiaryDetail->karbohidrat),
            'total_serat' => DB::raw('total_serat - ' . $foodDiaryDetail->serat),
            'total_abu' => DB::raw('total_abu - ' . $foodDiaryDetail->abu),
            'total_kalsium' => DB::raw('total_kalsium - ' . $foodDiaryDetail->kalsium),
            'total_fosfor' => DB::raw('total_fosfor - ' . $foodDiaryDetail->fosfor),
            'total_besi' => DB::raw('total_besi - ' . $foodDiaryDetail->besi),
            'total_natrium' => DB::raw('total_natrium - ' . $foodDiaryDetail->natrium),
            'total_kalium' => DB::raw('total_kalium - ' . $foodDiaryDetail->kalium),
            'total_tembaga' => DB::raw('total_tembaga - ' . $foodDiaryDetail->tembaga),
            'total_seng' => DB::raw('total_seng - ' . $foodDiaryDetail->seng),
            'total_retinol' => DB::raw('total_retinol - ' . $foodDiaryDetail->retinol),
            'total_b_karoten' => DB::raw('total_b_karoten - ' . $foodDiaryDetail->b_karoten),
            'total_karoten_total' => DB::raw('total_karoten_total - ' . $foodDiaryDetail->karoten_total),
            'total_thiamin' => DB::raw('total_thiamin - ' . $foodDiaryDetail->thiamin),
            'total_riboflamin' => DB::raw('total_riboflamin - ' . $foodDiaryDetail->riboflamin),
            'total_niasin' => DB::raw('total_niasin - ' . $foodDiaryDetail->niasin),
            'total_vitamin_c' => DB::raw('total_vitamin_c - ' . $foodDiaryDetail->vitamin_c),
            'jumlah_makanan' => DB::raw('jumlah_makanan - 1')
        );
        return $updatedFoodDiary;
    }
}
