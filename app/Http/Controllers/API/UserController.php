<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Food;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        if(Auth::user()->hasRole('user')){
            return 'This is protected dashboard for user only';
        } elseif(Auth::user()->hasRole('doctor')) {
            return 'This is protected dashboard for doctor only';
        } elseif(Auth::user()->hasRole('admin')) {
            return 'This is protected dashboard admin only';
        }
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
