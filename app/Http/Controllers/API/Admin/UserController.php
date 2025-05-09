<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    protected UserService $userService;
    protected AuthService $authService;
    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Admin: Retrieve a paginated list of users based on optional filter criteria.
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

        }catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while retrieving user data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: Create a new user account.
     *
     * @authenticated
     *
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function store(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerUser($request->validated());

            return response()->json([
                'success' => true,
                'status' => Response::HTTP_CREATED,
                'message' => 'User account created successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(),
                ],
            ], Response::HTTP_CREATED);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong while creating user account',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Admin: Display the specified user data.
     *
     * @urlParam user int required The ID of the user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'status' => Response::HTTP_OK,
                'message' => 'User data successfully retrieved',
                'data' => $user
            ], Response::HTTP_OK);

        }catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while retrieving user data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: Update the specified user profile.
     *
     * @urlParam user int required The ID of the user.
     *
     * @param UpdateUserProfileRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserProfileRequest $request, User $user): JsonResponse
    {
        try {
            $result = $this->userService->updateAuthUserProfile($request->validated(), $user);
            return response()->json([
                'success' => true,
                'status' => Response::HTTP_OK,
                'message' => 'User profile successfully updated',
                'data' => [
                    'id' => $result->id,
                    'name' => $result->name,
                    'email' => $result->email,
                    'email_verified_at' => $result->email_verified_at,
                    'tgl_lahir' => $result->tgl_lahir,
                    'no_telp' => $result->no_telp,
                    'gender' => $result->gender,
                    'tinggi_badan' => $result->tinggi_badan,
                    'berat_badan' => $result->berat_badan,
                    'tingkat_aktivitas' => $result->tingkat_aktivitas,
                    'goal' => $result->goal
                ]
            ], Response::HTTP_OK);

        }catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while updating user profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: Delete the specified user account.
     *
     * @urlParam user int required The ID of the user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'status' => Response::HTTP_NO_CONTENT,
                'message' => 'User account successfully deleted',
            ], Response::HTTP_NO_CONTENT);

        }catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while deleting user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
