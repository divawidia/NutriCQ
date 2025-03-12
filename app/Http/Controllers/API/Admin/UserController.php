<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Dedoc\Scramble\Attributes\QueryParameter;
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
     * Retrieve a paginated list of users based on optional filter criteria for admin.
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[QueryParameter('search', description: 'Search keyword for users.', type: 'string', default: null, example: 'John')]
    #[QueryParameter('gender', description: 'Filter by gender (male or female).', type: 'string', default: null, example: 'male')]
    #[QueryParameter('minHeight', description: 'Minimum height in centimeters.', type: 'int', default: null, example: 150)]
    #[QueryParameter('maxHeight', description: 'Maximum height in centimeters.', type: 'int', default: null, example: 180)]
    #[QueryParameter('minWeight', description: 'Minimum weight in kilograms.', type: 'int', default: null, example: 50)]
    #[QueryParameter('maxWeight', description: 'Maximum weight in kilograms.', type: 'int', default: null, example: 90)]
    #[QueryParameter('activityLevel', description: 'Filter by activity level.', type: 'string', default: null, example: 'lightly_active')]
    #[QueryParameter('status', description: 'Filter by user status.', type: 'string', default: null, example: 'active')]
    #[QueryParameter('page', description: 'Page number for pagination. Defaults to 1.', type: 'int', default: 1, example: 2)]
    #[QueryParameter('perPage', description: 'Number of items per page. Defaults to 10.', type: 'int', default: 10, example: 20)]
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
