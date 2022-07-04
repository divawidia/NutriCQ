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
            $id->update(FoodDiary::updateWhenFoodDeleted($detail_id));

            $calculateFood = Food::calculateFood($servingSize, $food->id);
            $detail_id->update(FoodDiaryDetail::calculateFoodForFoodDiaryDetail($id->id, $food->id, $calculateFood, $servingSize));
            $id->update(FoodDiary::updateWhenFoodAdded($calculateFood));

            return response()->json([
                'message' => 'successfully updated food serving size',
                'data' => $id->with('foodDiaryDetails')->where('id', $id->id)->get()
            ], Response::HTTP_OK);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized User'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function destroy_food_detail(FoodDiary $id, $detail_id)
    {
        // dd($detail_id);
        $detail = FoodDiaryDetail::find($detail_id);
        $authUserId = auth()->user()->id;

        //mengurangi nilai nutrisi pada food diary sebelum food detail dihapus
        if ($id->user_id == $authUserId) {
            $id->update(FoodDiary::updateWhenFoodDeleted($detail));
        }
        else{
            return response()->json(['message' => 'Unauthorized User'], Response::HTTP_UNAUTHORIZED);
        }

        FoodDiaryDetail::destroy($detail_id);

        return response()->json([
            'message' => 'successfully deleted'
        ], Response::HTTP_OK);
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

        return response()->json([
            'message' => 'Data successfully retrieved',
            'data' => $food
        ], Response::HTTP_OK);
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

        $calculateFood = Food::calculateFood($servingSize, $food->id);

        return response()->json([
            'message' => 'Data successfully retrieved',
            'data' => $calculateFood
        ], Response::HTTP_OK);
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
        $foodId = $food->id;

        //return error message if request not passing the validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $calculateFood = Food::calculateFood($servingSize, $foodId);
        $authUserId = auth()->user()->id;
        $foodDiary = FoodDiary::find($foodDiaryId);

        //cek id user yg sedang login sama dengan user_id di data food_diaries supaya user tidak bisa mengubah food diarynya user lain
        //jika sama, maka bisa diproses memasukan makanan ke food diary
        //jika tidak, maka return error unauthorized user
        if ($foodDiary->user_id == $authUserId) {
            $foodCalculated = FoodDiaryDetail::calculateFoodForFoodDiaryDetail($foodDiaryId, $foodId, $calculateFood, $servingSize);

            FoodDiaryDetail::create($foodCalculated);

            $foodDiary->update(FoodDiary::updateWhenFoodAdded($calculateFood));
        }
        else{
            return response()->json([
                'message' => 'Unauthorized User'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $food_diary = FoodDiary::with('foodDiaryDetails')->where('id', $foodDiaryId)->get();
        return response()->json([
            'message' => 'Food data successfully added to food diary',
            'data' => $food_diary
        ], Response::HTTP_CREATED);
    }

    public function index()
    {
        $foods = Food::all();
        return response()->json([
            'message' => 'Data successfully retrieved',
            'data' => $foods
        ], Response::HTTP_OK);
    }

    public function show(Food $food)
    {
        return response()->json([
            'message' => 'Data successfully retrieved',
            'data' => $food
        ], Response::HTTP_OK);
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
        return response()->json([
            'message' => 'Data successfully created',
            'data' => $food
        ], Response::HTTP_CREATED);
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

        return response()->json([
            'message' => 'Data successfully updated',
            'data' => $food
        ], Response::HTTP_OK);
    }

    public function destroy(Food $food)
    {
        $food->delete();

        return response()->json([
            'message' => 'successfully deleted'
        ], Response::HTTP_OK);
    }
}
