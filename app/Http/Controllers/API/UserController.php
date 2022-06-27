<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('user')) {
            return view('userdash');
        } elseif (Auth::user()->hasRole('doctor')) {
            return view('doctordash');
            // return redirect('/doctor/dashboard')
        } elseif (Auth::user()->hasRole('admin')) {
            return 'This is protected dashboard admin only';
        }
    }
}
