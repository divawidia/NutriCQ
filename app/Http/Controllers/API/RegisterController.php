<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register_user(Request $request)
    {
        $users = User::where('email', '=', $request->input('email'))->first();
        // Check if user exist or not
        if ($users != null) {
            // User exist
            return response([
                'message' => "Email has been registered!"
            ], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:3|confirmed',
            'no_telp' => 'required|string'
        ]);

        // store data to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp
        ]);

        $user->attachRole('user');

        // result with token
        $token = $user->createToken('user')->plainTextToken;
        return response()->json([
            'status' => 'Success',
            'message' => 'Successfull registgered',
            'token' => $token,
            'roles' => $user->roles->first()->name
        ], 201);
    }

    public function register_doctor(Request $request)
    {
        $users = User::where('email', '=', $request->input('email'))->first();
        if ($users != null) {
            // User exist
            return response([
                'message' => "Email has been registered!"
            ], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:3|confirmed',
            'no_telp' => 'required|string',
            'cv' => 'required|file',
            'license' => 'required|file',
        ]);

        // store data to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'cv' => $request->cv,
            'license' => $request->license,
        ]);

        $user->attachRole('doctor');

        // result with token
        $token = $user->createToken('doctor')->plainTextToken;
        return response()->json([
            'status' => 'Success',
            'message' => 'Successfull register',
            'token' => $token,
            'roles' => $user->roles->first()->name
        ], 201);
    }
}
