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
        $goal = auth()->user()->goals;

        if (count($goal) > 0) {
            return response()->json([
                'message' => 'success',
                'data' => $goal
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'user has no goal data',
                'data' => $goal
            ], Response::HTTP_OK);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_air' => 'numeric',
            'total_energi' => 'numeric',
            'total_protein' => 'numeric',
            'total_lemak' => 'numeric',
            'total_karbohidrat' => 'numeric',
            'total_serat' => 'numeric',
            'total_abu' => 'numeric',
            'total_kalsium' => 'numeric',
            'total_fosfor' => 'numeric',
            'total_besi' => 'numeric',
            'total_natrium' => 'numeric',
            'total_kalium' => 'numeric',
            'total_tembaga' => 'numeric',
            'total_seng' => 'numeric',
            'total_retinol' => 'numeric',
            'total_b_karoten' => 'numeric',
            'total_karoten_total' => 'numeric',
            'total_thiamin' => 'numeric',
            'total_riboflamin' => 'numeric',
            'total_niasin' => 'numeric',
            'total_vitamin_c' => 'numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Goal::where('user_id', '=', $this->authUserId())->exists()) {
            return response()->json([
                'message' => 'Goal only can be stored once',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $goal = auth()->user()->goals()->create($request->all());
            auth()->user()->goalHistories()->create($request->all());

            return response()->json([
                'message' => 'success',
                'data' => $goal
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Goal $id)
    {
        $data = auth()->user()->goals()->where('id', $id->id)->get();

        if ($this->authUserId() == $id->user_id) {
            return response()->json([
                'message' => 'Data successfully retrieved',
                'data' => $data
            ], Response::HTTP_OK);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized User'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Goal $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_air' => 'numeric',
            'total_energi' => 'numeric',
            'total_protein' => 'numeric',
            'total_lemak' => 'numeric',
            'total_karbohidrat' => 'numeric',
            'total_serat' => 'numeric',
            'total_abu' => 'numeric',
            'total_kalsium' => 'numeric',
            'total_fosfor' => 'numeric',
            'total_besi' => 'numeric',
            'total_natrium' => 'numeric',
            'total_kalium' => 'numeric',
            'total_tembaga' => 'numeric',
            'total_seng' => 'numeric',
            'total_retinol' => 'numeric',
            'total_b_karoten' => 'numeric',
            'total_karoten_total' => 'numeric',
            'total_thiamin' => 'numeric',
            'total_riboflamin' => 'numeric',
            'total_niasin' => 'numeric',
            'total_vitamin_c' => 'numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($this->authUserId() == $id->user_id) {
            $id->update($request->all());
            auth()->user()->goalHistories()->create($request->all());

            return response()->json([
                'message' => 'success',
                'data' => $id
            ], Response::HTTP_OK);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized User'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Goal $id)
    {
        if ($this->authUserId() == $id->user_id) {
            $id->delete();

            return response()->json([
                'message' => 'Goals data successfully deleted'
            ], Response::HTTP_OK);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized User'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
