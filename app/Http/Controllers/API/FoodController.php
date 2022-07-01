<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\User;
use App\Models\FoodDiary;
use App\Models\FoodDetail;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        //dd($user->id);
        $diary = FoodDiary::where('user_id', $user->id)->latest()->get();

        // return view('food.index', [
        //     'title' => 'Food Diary',
        //     'diarys' => $diary
        // ]);

        if ($diary) {
            return response()->json([
                'message' => 'success',
                'status_code' => 200,
                'data' => $diary
            ]);
        } else {
            return response()->json([
                'message' => 'data not found.',
                'status_code' => 404
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FoodDiary $id)
    {
        // dd($id->id);
        $data = FoodDetail::where('food_diary_id', $id->id)->get();
        //dd($data);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FoodDetail $detail_id)
    {
        $detail_id->update([
            'takaran_saji' => $request->takaran_saji
        ]);

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
            'data' => $detail_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FoodDiary::destroy($id);

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
        ]);
        //return redirect('foods');
    }

    public function destroy_food_detail($id, $detail_id)
    {
        // dd($detail_id);
        FoodDetail::destroy($detail_id);

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
        ]);
        //return redirect('foods');
    }

    public function search(Request $request)
    {
        $foodQuery = Food::with('foodCategory');
        $foodId = $request->food_id;

        if ($request->search){
            $foodQuery->where('name', 'LIKE', '%'.$request->search.'%');
        }
        if ($foodId){
            $foodQuery->where('id', $foodId);
        }

        $food = $foodQuery->get();
        return response($food, Response::HTTP_OK);
    }
}
