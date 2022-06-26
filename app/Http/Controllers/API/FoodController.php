<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodDiary;
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
        //
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
    public function show(Food $id)
    {
        return response($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

    public function calculate(Food $food, Request $request)
    {
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
        //dd($calculateFood[0]['id']);
        return response($calculateFood, Response::HTTP_OK);
    }
}
