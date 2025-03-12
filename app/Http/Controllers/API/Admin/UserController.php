<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            $gender = $request->get('gender');
            $minHeight = $request->get('minHeight');
            $maxHeight = $request->get('maxHeight');
            $minWeight = $request->get('minWeight');
            $maxWeight = $request->get('maxWeight');
            $activityLevel = $request->get('activityLevel');
            $status = $request->get('status');
            $page = $request->get('page');
            $perPage = $request->get('perPage');

            $data = $this->userService->index($search, $gender, $minHeight, $maxHeight, $minWeight, $maxWeight, $activityLevel, $status, $page, $perPage);

            return response()->json([
                'success' => true,
                'status' => Response::HTTP_OK,
                'message' => 'Users data successfully retrieved',
                'data' => $data->iteam(),
                'pagination' => [
                    'total' => $data->total(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                ]
            ], Response::HTTP_OK);

        }catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while retrieving user data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
