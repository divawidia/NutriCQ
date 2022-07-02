<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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

    public function update(Request $request, int $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,'.$user->id,
            'password' => 'required|string|min:6|confirmed',
            'tgl_lahir' => '',
            'no_telp' => 'required|string',
            'gender' => 'string',
            'cv' => '',
            'license' => '',
            'tinggi_badan' => 'int',
            'berat_badan' => 'int',
            'tingkat_aktivitas' => ''
        ]);

        if ($validator->fails()) return response()->json([
            'status' => 400,
            'error' => 'INVALID_REQUEST',
            'data' => $validator->errors(),
        ]);

        // $user = User::find($id);
        if (!$user) return response()->json([
            'status' => 404,
            'error' => 'RECORD_NOT_FOUND',
            'data' => null,
        ], 404);

        $user->name = $request->post('name', $user->name);
        $user->email = $request->post('email', $user->email);
        $user->password = Hash::make($request->post('password', $user->password));
        $user->tgl_lahir = $request->post('tgl_lahir', $user->tgl_lahir);
        $user->no_telp = $request->post('no_telp', $user->no_telp);
        $user->gender = $request->post('gender', $user->gender);
        $user->cv = $request->post('cv', $user->cv);
        $user->license = $request->post('license', $user->license);
        $user->tinggi_badan = $request->post('tinggi_badan', $user->tinggi_badan);
        $user->berat_badan = $request->post('berat_badan', $user->berat_badan);
        $user->tingkat_aktivitas = $request->post('tingkat_aktivitas', $user->tingkat_aktivitas);
        $user->save();

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
        ], 200);
    }
}
