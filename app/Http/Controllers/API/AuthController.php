<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laratrust\Middleware\LaratrustRole;

class AuthController extends Controller
{
<<<<<<< HEAD
=======
    // Register User
    public function register(Request $request)
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
            'tgl_lahir' => '',
            'no_telp' => 'required|string',
            'gender' => 'string',
            'cv' => '',
            'license' => '',
            'tinggi_badan' => 'int',
            'berat_badan' => 'int'
        ]);

        // store to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tgl_lahir' => $request->tgl_lahir,
            'no_telp' => $request->no_telp,
            'gender' => $request->gender,
            'cv' => $request->cv,
            'license' => $request->license,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan
        ]);

        $user->attachRole($request->role_id);
        // $user->attachRole('admin');

        // result with token
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'status' => 'Success',
            'message' => 'Successfull registgered',
            'token' => $token,
        ], Response::HTTP_CREATED);
    }
>>>>>>> 211a2e07248d6aa603939b82586614ac19609bbb

    //Login User
    public function indexUser()
    {
        return view('login');
    }

    public function loginUser(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //Check email
        $user = User::where('email', $fields['email'])->first();

<<<<<<< HEAD
        // Check role
        // $role = User::where('role',);
=======
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

    //Login ADMIN
    public function indexAdmin()
    {
        return 'This is API page for Login Admin';

    }

    public function loginAdmin(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);


        //Check email
        $user = User::where('email', $fields['email'])->first();
>>>>>>> 211a2e07248d6aa603939b82586614ac19609bbb

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

    //Login ADMIN
    // public function indexAdmin()
    // {
    //     return 'This is API page for Login Admin';
    // }

    // public function loginAdmin(Request $request)
    // {
    //     $fields = $request->validate([
    //         'email' => 'required|string',
    //         'password' => 'required|string'
    //     ]);

    //     //Check email
    //     $user = User::where('email', $fields['email'])->first();

    //     //Check Password
    //     if (!$user || !Hash::check($fields['password'], $user->password)) {
    //         return response([
    //             'message' => "Bad Crendential"
    //         ], 401);
    //     }

    //     $token = $user->createToken('myapptoken')->plainTextToken;

    //     $response = [
    //         'user' => $user,
    //         'token' => $token
    //     ];

    //     return response($response, 201);
    // }

    // public function index()
    // {
    //     //This could be view file
    //     return 'This is API for Login';
    // }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
