<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\Food;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get the authenticated user's profile data.
     *
     * @authenticated
     *
     * @return JsonResponse
     */
    public function showUserProfile(): JsonResponse
    {
        try {
            $user = auth()->user();
            return response()->json([
                'success' => true,
                'status' => Response::HTTP_OK,
                'message' => 'User data successfully retrieved',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'tgl_lahir' => $user->tgl_lahir,
                    'no_telp' => $user->no_telp,
                    'gender' => $user->gender,
                    'tinggi_badan' => $user->tinggi_badan,
                    'berat_badan' => $user->berat_badan,
                    'tingkat_aktivitas' => $user->tingkat_aktivitas,
                    'status' => $user->status
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

    public function updateAuthUserProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        $result = $this->userService->updateAuthUserProfile($request->validated(), auth()->user());
        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'message' => 'Your profile successfully updated',
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
                'tingkat_aktivitas' => $result->tingkat_aktivitas
            ]
        ], Response::HTTP_OK);
    }







    public function doctorIndex()
    {
        $doctor = User::whereRoleIs('doctor')->get();;
        return response($doctor, Response::HTTP_OK);
    }

    public function showDoctor(User $doctor)
    {
        return response($doctor, Response::HTTP_OK);
    }

    public function updateStatusDoctor(User $doctor, Request $request)
    {
        $doctorData = $doctor->whereRoleIs('doctor')->find($doctor->id);

        if ($doctorData != null){
            $validator = Validator::make($request->all(), [
                'status' => [
                    'required',
                    'string',
                    Rule::in(['active', 'inactive'])
                ]
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $doctor->update([
                'status' => $request->status
            ]);

            return response($doctor, Response::HTTP_OK);
        }
        else{
            return response()->json(['message' => 'User is not doctor'], Response::HTTP_UNAUTHORIZED);
        }
    }

}
