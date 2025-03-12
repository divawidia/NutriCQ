<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
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
     * Retrieve a paginated list of users based on optional filter criteria.
     *
     * Filters available:
     * - search (string): Keyword to search users by name or email.
     * - gender (string): Filter by gender ('male' or 'female').
     * - minHeight (int): Minimum height filter (in cm).
     * - maxHeight (int): Maximum height filter (in cm).
     * - minWeight (int): Minimum weight filter (in kg).
     * - maxWeight (int): Maximum weight filter (in kg).
     * - activityLevel (string): Activity level filter (e.g., 'sedentary', 'lightly_active').
     * - status (string): User status filter.
     *
     * Query Parameters:
     * @queryParam search string optional Search keyword for users.
     * @queryParam gender string optional Filter by gender (male or female).
     * @queryParam minHeight int optional Minimum height in centimeters.
     * @queryParam maxHeight int optional Maximum height in centimeters.
     * @queryParam minWeight int optional Minimum weight in kilograms.
     * @queryParam maxWeight int optional Maximum weight in kilograms.
     * @queryParam activityLevel string optional Filter by activity level.
     * @queryParam status string optional Filter by user status.
     * @queryParam page int optional Page number for pagination. Defaults to 1.
     * @queryParam perPage int optional Number of items per page. Defaults to 10.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Collect filters with default values
            $filters = $request->only([
                'search', 'gender', 'minHeight', 'maxHeight', 'minWeight', 'maxWeight', 'activityLevel', 'status'
            ]);

            $page = (int) $request->input('page', 1);
            $perPage = (int) $request->input('perPage', 10);

            $data = $this->userService->index(
                $filters['search'] ?? null,
                $filters['gender'] ?? null,
                $filters['minHeight'] ?? null,
                $filters['maxHeight'] ?? null,
                $filters['minWeight'] ?? null,
                $filters['maxWeight'] ?? null,
                $filters['activityLevel'] ?? null,
                $filters['status'] ?? null,
                $page,
                $perPage
            );

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
