<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
