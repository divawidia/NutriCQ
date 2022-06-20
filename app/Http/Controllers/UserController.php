<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'bail|required|regex:/^[a-zA-ZÑñ\s]+$/|max:255',
            'email' => 'bail|required|unique:users,email|email',
            'password' => 'bail|required|min:8|max:16',
            'tgl_lahir' => 'bail|required|date',
            'no_telp' => 'bail|required|regex:/^[0-9]*$/',
            'gender' => 'required',
            'roles_id' => 'bail|required|numeric',
            'cv' => 'bail|nullable|requred_if:role,2|image',
            'lincense' => 'bail|nullable|requred_if:role,2|image',
            'tinggi_badan' => 'bail|nullable|numeric|max:250',
            'berat_badan' => 'nullable|numeric',
            'tingkat_aktivitas' => 'nullable|numeric'
        ]);
    }
}
