<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Goal;
use App\Models\GoalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'message' => 'success',
            'data' => auth()->user()->goals
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_air' => 'required|numeric',
            'total_energi' => 'required|numeric',
            'total_protein' => 'required|numeric',
            'total_lemak' => 'required|numeric',
            'total_karbohidrat' => 'required|numeric',
            'total_serat' => 'required|numeric',
            'total_abu' => 'required|numeric',
            'total_kalsium' => 'required|numeric',
            'total_fosfor' => 'required|numeric',
            'total_besi' => 'required|numeric',
            'total_natrium' => 'required|numeric',
            'total_kalium' => 'required|numeric',
            'total_tembaga' => 'required|numeric',
            'total_seng' => 'required|numeric',
            'total_retinol' => 'required|numeric',
            'total_b_karoten' => 'required|numeric',
            'total_karoten_total' => 'required|numeric',
            'total_thiamin' => 'required|numeric',
            'total_riboflamin' => 'required|numeric',
            'total_niasin' => 'required|numeric',
            'total_vitamin_c' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        auth()->user()->goals()->update($request->all());
        auth()->user()->goalHistories()->create($request->all());

        return response()->json([
            'message' => 'success',
            'data' => auth()->user()->goals
        ], Response::HTTP_OK);

    }
}
