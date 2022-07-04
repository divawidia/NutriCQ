<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodDiary;
use App\Models\FoodDiaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FoodDiaryController extends Controller
{

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
            $calculateFood = Food::calculateFood($servingSize, $foodId);

            $foodCalculated = FoodDiaryDetail::calculateFoodForFoodDiaryDetail($foodDiary->id, $foodId, $calculateFood, $servingSize);

            FoodDiaryDetail::create($foodCalculated);

            $foodDiary->update(FoodDiary::updateWhenFoodAdded($calculateFood));
        }

        $foodDiary = $foodDiary->with('foodDiaryDetails')->where('id', $foodDiary->id)->get();

        //return response($foodDiary, Response::HTTP_CREATED);
        return response()->json([
            'message' => 'Data successfully created',
            'data' => $foodDiary
        ], Response::HTTP_CREATED);
    }

    public function show(FoodDiary $food_diary)
    {
        $foodDiary = auth()->user()->foodDiaries()->with('foodDiaryDetails')->where('id', $food_diary->id)->get();

        if ($this->authUserId() == $food_diary->user_id) {
            return response()->json([
                'message' => 'Data successfully retrieved',
                'data' => $foodDiary
            ], Response::HTTP_OK);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized User'
            ], Response::HTTP_UNAUTHORIZED);
        }
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
            $calculateFood = Food::calculateFood($servingSize, $foodId);

            $foodCalculated = FoodDiaryDetail::calculateFoodForFoodDiaryDetail($food_diary->id, $foodId, $calculateFood, $servingSize);

            $food_diary->foodDiaryDetails()->create($foodCalculated);

            $food_diary->update(FoodDiary::updateWhenFoodAdded($calculateFood));

            $foodDiary = $food_diary->with('foodDiaryDetails')->where('id', $food_diary->id)->get();

            return response()->json([
                'message' => 'Food data successfully added to food diary',
                'data' => $foodDiary
            ], Response::HTTP_CREATED);
        }
        else{
            return response()->json(['message' => 'Unauthorized User'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function destroy(FoodDiary $id)
    {
        if ($this->authUserId() == $id->user_id) {
            $id->delete();

            return response()->json([
                'message' => 'Food diary data successfully deleted'
            ], Response::HTTP_OK);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized User'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
