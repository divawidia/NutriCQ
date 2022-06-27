<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    //
    //Login User
    public function login()
    {
        return view('login');
    }

    // auth
    public function authenticate(Request $request)
    {

        $user = User::where('email', $request['email'])->firstOrFail();
        $credential = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // check user
        if (!Auth::attempt($credential)) {
            return back();
        }


        $user->createToken('authToken')->plainTextToken;

        $user = Auth::user();
        if ($user->hasRole('user')) {
            return redirect('/dashboard/user');
        } elseif ($user->hasRole('doctor')) {
            return redirect('/dashboard/doctor');
        } elseif ($user->hasRole('admin')) {
            return redirect('/dashboard/admin');
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $request->session()->invalidate();
        return redirect('/login');
    }
}
