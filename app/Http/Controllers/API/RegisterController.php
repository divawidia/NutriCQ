<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register_user(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'no_telp' => 'required|string'
        ]);

        $users = User::where('email', '=', $request->input('email'))->first();

        // Check if user exist or not
        if ($users != null) {
            // User exist
            return response([
                'message' => "Email has been registered!"
            ], 401);
        }

        // store data to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'status' => 'active'
        ]);

        $user->attachRole('user');
        $user->goals()->create();
        $user->goalHistories()->create();

        // result with token
        $token = $user->createToken('user')->plainTextToken;
        return response()->json([
            'status' => $user->status,
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
            'password' => 'required|string|min:6|confirmed',
            'no_telp' => 'required|string',
            'cv' => 'required|mimes:pdf|max:10000',
            'license' => 'required|mimes:pdf|max:10000',
        ]);

        // store data to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'cv' => $request->file('cv')->store('public/cv'),
            'license' => $request->file('license')->store('public/license'),
            'status' => 'inactive'
        ]);

        $user->attachRole('doctor');

        // result with token
        // $token = $user->createToken('doctor')->plainTextToken;
        return response()->json([
            'status' => $user->status,
            'message' => 'Waiting verification from admin',
            // 'token' => $token,
            'roles' => $user->roles->first()->name
        ], 201);
    }
}
