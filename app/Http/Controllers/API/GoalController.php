<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Goal;
use App\Models\GoalHistory;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = Goal::all();
        if (count($data) > 0) {
            return response()->json([
                'message' => 'success',
                'status_code' => 200,
                'data' => $data
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user_id = $request->input('user_id');

        if (Goal::where('user_id', '=', $user_id)->exists()) {
            return response()->json([
                'message' => 'Goal only can be stored once',
                'status_code' => 403,
            ], 403);
        } else {
            Goal::create($request->all());
            GoalHistory::create($request->all());
            
            return response()->json([
                'message' => 'success',
                'status_code' => 201,
            ], 201);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = Goal::find($id);

        if ($data) {
            return response()->json([
                'message' => 'success',
                'status_code' => 200,
                'data' => $data
            ], 200);
        } 
        else {
            return response()->json([
                'message' => 'data not found.',
                'status_code' => 404
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Goal $id)
    {
        // $user = auth()->user()->goals();
        // $goal = auth()->user()->goals()->update($request->all());
        $id->update($request->all());
        GoalHistory::create($request->all());

        return response()->json([
            'message' => 'success',
            'status_code' => 201
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Goal $id)
    {
        $id->delete();

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
        ]);
    }
}
