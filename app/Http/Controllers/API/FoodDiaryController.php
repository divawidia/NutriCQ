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

class FoodDiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $foodDiary = auth()->user()->foodDiaries;

        if (count($foodDiary) > 0) {
            return response()->json([
                'message' => 'success',
                'data' => $foodDiary
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'user has no food diary data',
                'data' => $foodDiary
            ], Response::HTTP_OK);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_food_diary' => 'required|date|unique:food_diaries,tgl_food_diary,NULL,id,user_id,'.auth()->user()->id,
            'food_id' => 'required_without:tgl_food_diary|required_with:serving_size|numeric',
            'serving_size' => 'required_without:tgl_food_diary|required_with:food_id|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $servingSize = $request->serving_size;
        $foodId = $request->food_id;

        $foodDiary = auth()->user()->foodDiaries()->create(array('tgl_food_diary' => $request->tgl_food_diary));

        if ($request->serving_size && $request->food_id){
            $calculateFood = FoodDiaryDetail::calculateFood($servingSize, $foodId);

            $foodCalculated = array(
                'food_diary_id' => $foodDiary['id'],
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
            FoodDiaryDetail::create($foodCalculated);

            $foodDiary->update([
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
            ]);
        }

        $foodDiary = $foodDiary->where('id', $foodDiary->id)->with('foodDiaryDetails')->get();

        //return response($foodDiary, Response::HTTP_CREATED);
        return response()->json([
            'message' => 'Data successfully created',
            'data' => $foodDiary
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FoodDiary $food_diary)
    {
        $foodDiary = auth()->user()->foodDiaries()->where('id', $food_diary->id)->get();

        return response()->json([
            'message' => 'Data successfully retrieved',
            'data' => $foodDiary
        ], Response::HTTP_OK);
    }

    public function addFoodToExistingFoodDiary(Request $request, FoodDiary $food_diary)
    {
        $validator = Validator::make($request->all(), [
            'food_id' => 'required_with:serving_size|numeric',
            'serving_size' => 'required_with:food_id|numeric'
        ]);

        $servingSize = $request->serving_size;
        $foodId = $request->food_id;

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        if (auth()->user()->foodDiaries()->find($food_diary->id)){
            $calculateFood = FoodDiaryDetail::calculateFood($servingSize, $foodId);

            $food_diary->foodDiaryDetails()->create(array(
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
            ));

            $food_diary->update([
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

            $foodDiary = $food_diary->where('id', $food_diary->id)->with('foodDiaryDetails')->get();

            return response()->json([
                'message' => 'Food data successfully added to food diaryZ',
                'data' => $foodDiary
            ], Response::HTTP_CREATED);
        }
        else{
            return response()->json(['message' => 'Unauthorized User'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function destroy($id)
    {
        FoodDiary::destroy($id);

        return response()->json([
            'message' => 'successfully deleted'
        ], Response::HTTP_OK);

    }
}
