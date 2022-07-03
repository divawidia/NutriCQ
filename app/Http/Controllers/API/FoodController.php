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
    public function showFoodDiaryDetail(FoodDiary $id)
    {

        $data = FoodDiaryDetail::where('food_diary_id', $id->id)->get();

        if($data)
        {
            return response()->json([
                'message' => 'success',
                'status_code' => 200,
                'data' => $data
            ]);
        }else
        {
            return response()->json([
                'message' => 'data not found.',
                'status_code' => 404
            ]);
        }
    }

    public function updateFoodDiaryDetail(Request $request, FoodDiary $id, FoodDiaryDetail $detail_id)
    {
        // $detail_id->update([
        //     'takaran_saji' => $request->takaran_saji
        // ]);

        //validate request
        $validator = Validator::make($request->all(), [
            'serving_size' => 'required|numeric',
        ]);

        //cek jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $servingSize = $request->serving_size;
        $food = Food::find($detail_id->foods->id);

        $authUserId = auth()->user()->id;

        //mengurangi nilai fooddetail sebelumnya dari food diary agar dapat memperbarui nilai dari food diary saat takaran saji di update
        if ($id->user_id == $authUserId) {
            $id->update([
                'total_air' => DB::raw('total_air - ' . $detail_id->air),
                'total_energi' => DB::raw('total_energi - ' . $detail_id->energi),
                'total_protein' => DB::raw('total_protein - ' . $detail_id->protein),
                'total_lemak' => DB::raw('total_lemak - ' . $detail_id->lemak),
                'total_karbohidrat' => DB::raw('total_karbohidrat - ' . $detail_id->karbohidrat),
                'total_serat' => DB::raw('total_serat - ' . $detail_id->serat),
                'total_abu' => DB::raw('total_abu - ' . $detail_id->abu),
                'total_kalsium' => DB::raw('total_kalsium - ' . $detail_id->kalsium),
                'total_fosfor' => DB::raw('total_fosfor - ' . $detail_id->fosfor),
                'total_besi' => DB::raw('total_besi - ' . $detail_id->besi),
                'total_natrium' => DB::raw('total_natrium - ' . $detail_id->natrium),
                'total_kalium' => DB::raw('total_kalium - ' . $detail_id->kalium),
                'total_tembaga' => DB::raw('total_tembaga - ' . $detail_id->tembaga),
                'total_seng' => DB::raw('total_seng - ' . $detail_id->seng),
                'total_retinol' => DB::raw('total_retinol - ' . $detail_id->retinol),
                'total_b_karoten' => DB::raw('total_b_karoten - ' . $detail_id->b_karoten),
                'total_karoten_total' => DB::raw('total_karoten_total - ' . $detail_id->karoten_total),
                'total_thiamin' => DB::raw('total_thiamin - ' . $detail_id->thiamin),
                'total_riboflamin' => DB::raw('total_riboflamin - ' . $detail_id->riboflamin),
                'total_niasin' => DB::raw('total_niasin - ' . $detail_id->niasin),
                'total_vitamin_c' => DB::raw('total_vitamin_c - ' . $detail_id->vitamin_c),
                'jumlah_makanan' => DB::raw('jumlah_makanan - 1')
            ]);
        }
        else{
            return response()->json(['message' => 'Unauthorized User'], Response::HTTP_UNAUTHORIZED);
        }

        $detail_id->update(['takaran_saji' => $servingSize]);

        // $calculateFood = calculateFood($food, $servingSize);
        $calculateFood = FoodDiaryDetail::calculateFood($servingSize, $food->id);

        if ($id->user_id == $authUserId) {
            $id->update([
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

        return response()->json([
            'message' => 'successfully added food',
            'data' => $id
        ]);
    }

    public function destroy_food_detail(FoodDiary $id, $detail_id)
    {
        // dd($detail_id);
        $detail = FoodDiaryDetail::find($detail_id);
        $authUserId = auth()->user()->id;
        //mengurangi nilai nutrisi pada food diary sebelum food detail dihapus
        if ($id->user_id == $authUserId) {
            $id->update([
                'total_air' => DB::raw('total_air - ' . $detail->air),
                'total_energi' => DB::raw('total_energi - ' . $detail->energi),
                'total_protein' => DB::raw('total_protein - ' . $detail->protein),
                'total_lemak' => DB::raw('total_lemak - ' . $detail->lemak),
                'total_karbohidrat' => DB::raw('total_karbohidrat - ' . $detail->karbohidrat),
                'total_serat' => DB::raw('total_serat - ' . $detail->serat),
                'total_abu' => DB::raw('total_abu - ' . $detail->abu),
                'total_kalsium' => DB::raw('total_kalsium - ' . $detail->kalsium),
                'total_fosfor' => DB::raw('total_fosfor - ' . $detail->fosfor),
                'total_besi' => DB::raw('total_besi - ' . $detail->besi),
                'total_natrium' => DB::raw('total_natrium - ' . $detail->natrium),
                'total_kalium' => DB::raw('total_kalium - ' . $detail->kalium),
                'total_tembaga' => DB::raw('total_tembaga - ' . $detail->tembaga),
                'total_seng' => DB::raw('total_seng - ' . $detail->seng),
                'total_retinol' => DB::raw('total_retinol - ' . $detail->retinol),
                'total_b_karoten' => DB::raw('total_b_karoten - ' . $detail->b_karoten),
                'total_karoten_total' => DB::raw('total_karoten_total - ' . $detail->karoten_total),
                'total_thiamin' => DB::raw('total_thiamin - ' . $detail->thiamin),
                'total_riboflamin' => DB::raw('total_riboflamin - ' . $detail->riboflamin),
                'total_niasin' => DB::raw('total_niasin - ' . $detail->niasin),
                'total_vitamin_c' => DB::raw('total_vitamin_c - ' . $detail->vitamin_c),
                'jumlah_makanan' => DB::raw('jumlah_makanan - 1')
            ]);
        }
        else{
            return response()->json(['message' => 'Unauthorized User'], Response::HTTP_UNAUTHORIZED);
        }


        FoodDiaryDetail::destroy($detail_id);

        return response()->json([
            'message' => 'success',
            'status_code' => 204,
        ], Response::HTTP_NO_CONTENT);
        //return redirect('foods');
    }

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

        $calculateFood = FoodDiaryDetail::calculateFood($servingSize, $food->id);

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

        $calculateFood = FoodDiaryDetail::calculateFood($servingSize, $food->id);

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

    public function index()
    {
        $foods = Food::all();
        return response($foods, Response::HTTP_OK);
    }

    public function show(Food $food)
    {
        return response($food, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:foods,name',
            'sumber' => 'required|string',
            'air' => 'required|numeric',
            'energi' => 'required|numeric',
            'protein' => 'required|numeric',
            'lemak' => 'required|numeric',
            'karbohidrat' => 'required|numeric',
            'serat' => 'required|numeric',
            'abu' => 'required|numeric',
            'kalsium' => 'required|numeric',
            'fosfor' => 'required|numeric',
            'besi' => 'required|numeric',
            'natrium' => 'required|numeric',
            'kalium' => 'required|numeric',
            'tembaga' => 'required|numeric',
            'seng' => 'required|numeric',
            'retinol' => 'required|numeric',
            'b_karoten' => 'required|numeric',
            'karoten_total' => 'required|numeric',
            'thiamin' => 'required|numeric',
            'riboflamin' => 'required|numeric',
            'niasin' => 'required|numeric',
            'vitamin_c' => 'required|numeric',
            'porsi_berat_dapat_dimakan' => 'required|numeric',
            'category_id' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $food = Food::create($request->all());
        return response($food, Response::HTTP_CREATED);
    }

    public function update(Food $food, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|unique:foods,name',
            'sumber' => 'string',
            'air' => 'numeric',
            'energi' => 'numeric',
            'protein' => 'numeric',
            'lemak' => 'numeric',
            'karbohidrat' => 'numeric',
            'serat' => 'numeric',
            'abu' => 'numeric',
            'kalsium' => 'numeric',
            'fosfor' => 'numeric',
            'besi' => 'numeric',
            'natrium' => 'numeric',
            'kalium' => 'numeric',
            'tembaga' => 'numeric',
            'seng' => 'numeric',
            'retinol' => 'numeric',
            'b_karoten' => 'numeric',
            'karoten_total' => 'numeric',
            'thiamin' => 'numeric',
            'riboflamin' => 'numeric',
            'niasin' => 'numeric',
            'vitamin_c' => 'numeric',
            'porsi_berat_dapat_dimakan' => 'numeric',
            'category_id' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $food->update($request->all());

        return response($food, Response::HTTP_OK);
    }

    public function destroy(Food $food)
    {
        $food->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
