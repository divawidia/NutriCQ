<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function index()
    {
        //This could be view file
        return 'This is API for Login';
    }
    
    public function indexUser()
    {
        return 'This is API page for Login User';
    }

    public function loginUser()
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        //Check email
        $user = User::where('email', $fields['email'])->where('status', 'active')->first();
        
        //check if user active
        if($user == null)
        {
            return response([
                'message' => "Account Inactive"
            ], 401);
        }

        //Check Password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => "Bad Crendential"
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function indexAdmin()
    {
        return 'This is API page for Login Admin';
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //Check email
        $user = User::where('email', $fields['email'])->where('status', 'active')->first();

        //Check Password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => "Bad Crendential"
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
