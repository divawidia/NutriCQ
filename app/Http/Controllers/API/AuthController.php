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
     *
     * @bodyParam name string required The user's full name. Example: John Doe
     * @bodyParam email string required The user's email address. Must be unique. Example: john@example.com
     * @bodyParam password string required The user's password. Minimum 8 characters, must include letters, numbers, mixed case. Example: Secret123
     * @bodyParam password_confirmation string required Must match the password field. Example: Secret123
     * @bodyParam tgl_lahir date required Date of birth. Must be a date before today. Example: 2000-01-01
     * @bodyParam no_telp string required The user's phone number. Example: 081234567890
     * @bodyParam gender string required The user's gender. Must be either `male` or `female`. Example: male
     * @bodyParam tinggi_badan integer required Height in centimeters. Example: 170
     * @bodyParam berat_badan integer required Weight in kilograms. Example: 65
     * @bodyParam tingkat_aktivitas string required Activity level. One of: sedentary, lightly_active, moderately_active, very_active, extra_active. Example: moderately_active
     * @bodyParam status string required The account status. Must be either `active` or `inactive`. Example: active
     */
    public function registerUser(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated(), 'user');

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
            $user = $this->authService->register($request->validated(), 'doctor');

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
