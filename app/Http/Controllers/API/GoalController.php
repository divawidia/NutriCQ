<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Goal::all();
        
        if ($data) {
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Goal::create($request->all());

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function show(Goal $id)
    {
        $data = Goal::find($id);
        
        if ($data) {
            return response()->json([
                'message' => 'success',
                'status_code' => 200,
                'data' => $data
            ]);
        } 
        else {
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
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $id)
    {
        $id->update($request->all());

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Goal  $goal
     * @return \Illuminate\Http\Response
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
