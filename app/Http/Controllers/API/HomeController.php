<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function store(Request $request)
    {
        $userid = Auth::user();
        $data = new booking;
        $data->name = $userid->name;
        $data->tgl_booking = $request->tgl_booking;
        $data->deskripsi_booking = $request->deskripsi_booking;
        $data->status = 'Waiting';
        $data->user_id = $userid->id;

        $data->save();

        return response()->json([
            'Tanggal Booking' => $data->tgl_booking,
            'Deskripsi Booking' => $data->deskripsi_booking,
            'Status' => $data->status,
            'message' => 'Booking successfull!'
        ], 201);
 
    }

    public function my_booking()
    {
        if (Auth::id()) {
            $userid = Auth::user()->id;
            $bookings = booking::where('user_id', $userid)->get();

            return response()->json([
                $bookings
            ], 201);
        }
    }

    public function cancel_booking($id)
    {
        $data = booking::find($id);
        $data->delete();
        return response()->json([
            'message' => 'Cancell successfully!'
        ], 201);
    }
}
