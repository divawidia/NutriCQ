<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodDiary;
use App\Models\FoodDiaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FoodController extends Controller
{
    public function search(Request $request)
    {
        //validasi
        $validator = Validator::make($request->all(), [
            'search' => 'required_without:food_id|regex:/^[a-zA-Z]+$/u',
            'food_id' => 'required_without:search|numeric'
        ],
        [
            'search.regex' => 'Only alphabets are allowed',
        ]);

        $foodQuery = Food::with('foodCategory');
        $foodId = $request->food_id;

        //cek validasi, jika gagal maka return error message
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->search){
            $foodQuery->where('name', 'LIKE', '%'.$request->search.'%');
        }
        if ($foodId){
            $foodQuery->where('id', $foodId);
        }

        $food = $foodQuery->get();
        return response($food, Response::HTTP_OK);
    }

    public function calculate(Food $food, Request $request)
    {
        //validasi
        $validator = Validator::make($request->all(), [
            'serving_size' => 'required|numeric'
        ]);

        //cek validasi, jika gagal maka return error message
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $servingSize = $request->serving_size;
        $calculateFood = $food->with('foodCategory')->selectRaw(
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
            ->where('id', $food->id)
            ->get();

        return response($calculateFood, Response::HTTP_OK);
    }

    public function storeCalculatedFoodToFoodDiary(Food $food, Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'serving_size' => 'required|numeric',
            'food_diary_id' => 'required|numeric'
        ]);

        $servingSize = $request->serving_size;
        $foodDiaryId = $request->food_diary_id;

        //return error message if request not passing the validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $calculateFood = $food->with('foodCategory')->selectRaw(
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
            ->where('id', $food->id)
            ->get();

        $authUserId = auth()->user()->id;
        $foodDiary = FoodDiary::find($foodDiaryId);

        //cek id user yg sedang login sama dengan user_id di data food_diaries supaya user tidak bisa mengubah food diarynya user lain
        //jika sama, maka bisa diproses memasukan makanan ke food diary
        //jika tidak, maka return error unauthorized user
        if ($foodDiary->user_id == $authUserId) {
            FoodDiaryDetail::create(array(
                'food_id' => $food->id,
                'food_diary_id' => $foodDiaryId,
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
            ));

            $foodDiary->update([
                'total_air' => DB::raw('total_air + ' . $calculateFood[0]['air']),
                'total_energi' => DB::raw('total_energi + ' . $calculateFood[0]['energi']),
                'total_protein' => DB::raw('total_protein + ' . $calculateFood[0]['protein']),
                'total_lemak' => DB::raw('total_lemak + ' . $calculateFood[0]['lemak']),
                'total_karbohidrat' => DB::raw('total_karbohidrat + ' . $calculateFood[0]['karbohidrat']),
                'total_serat' => DB::raw('total_serat + ' . $calculateFood[0]['serat']),
                'total_abu' => DB::raw('total_abu + ' . $calculateFood[0]['abu']),
                'total_kalsium' => DB::raw('total_kalsium + ' . $calculateFood[0]['kalsium']),
                'total_fosfor' => DB::raw('total_fosfor + ' . $calculateFood[0]['fosfor']),
                'total_besi' => DB::raw('total_besi + ' . $calculateFood[0]['besi']),
                'total_natrium' => DB::raw('total_natrium + ' . $calculateFood[0]['natrium']),
                'total_kalium' => DB::raw('total_kalium + ' . $calculateFood[0]['kalium']),
                'total_tembaga' => DB::raw('total_tembaga + ' . $calculateFood[0]['tembaga']),
                'total_seng' => DB::raw('total_seng + ' . $calculateFood[0]['seng']),
                'total_retinol' => DB::raw('total_retinol + ' . $calculateFood[0]['retinol']),
                'total_b_karoten' => DB::raw('total_b_karoten + ' . $calculateFood[0]['b_karoten']),
                'total_karoten_total' => DB::raw('total_karoten_total + ' . $calculateFood[0]['karoten_total']),
                'total_thiamin' => DB::raw('total_thiamin + ' . $calculateFood[0]['thiamin']),
                'total_riboflamin' => DB::raw('total_riboflamin + ' . $calculateFood[0]['riboflamin']),
                'total_niasin' => DB::raw('total_niasin + ' . $calculateFood[0]['niasin']),
                'total_vitamin_c' => DB::raw('total_vitamin_c + ' . $calculateFood[0]['vitamin_c']),
                'jumlah_makanan' => DB::raw('jumlah_makanan + 1')
            ]);
        }
        else{
            return response()->json(['message' => 'Unauthorized User'], Response::HTTP_UNAUTHORIZED);
        }

        $food_diary = FoodDiary::where('id', $foodDiaryId)->get();
        return response()->json([
            'message' => 'successfully added food',
            'data' => $food_diary
        ], Response::HTTP_CREATED);
    }
}
