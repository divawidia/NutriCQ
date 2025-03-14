<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\RegisterDoctorRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Register a new admin account.
     *
     * @unauthenticated
     *
     * @param  RegisterAdminRequest  $request
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function registerAdmin(RegisterAdminRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerAdmin($request->validated());

            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Admin registered successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(),
                ],
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while registering admin',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register a new user account.
     *
     * @unauthenticated
     *
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function registerUser(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerUser($request->validated());

            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'User registered successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(),
                ],
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while registering user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register a new doctor account.
     *
     * @unauthenticated
     *
     * @param RegisterDoctorRequest $request
     * @return JsonResponse
     */
    public function registerDoctor(RegisterDoctorRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerDoctor($request->validated());

            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Doctor registered successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(),
                ],
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong while registering doctor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Authenticate a user and return an access token.
     *
     * @param  LoginRequest  $request
     * @return JsonResponse
     *
     * @bodyParam email string required The user's email. Example: john@example.com
     * @bodyParam password string required The user's password. Example: secret123
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $authData = $this->authService->login($data['email'], $data['password']);

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Login successful',
                'data' => [
                    'token' => $authData['token'],
                    'user' => [
                        'id' => $authData['user']->id,
                        'name' => $authData['user']->name,
                        'email' => $authData['user']->email,
                        'role' => $authData['user']->getRoleNames()->first(),
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Log out the currently authenticated user.
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
                'success' => true,
                'status' => Response::HTTP_OK,
                'message' => 'Logged out successfully',
            ], Response::HTTP_OK);
    }
}
