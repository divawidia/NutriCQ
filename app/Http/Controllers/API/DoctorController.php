<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function bookinglist()
    {
        $data = booking::all();
        // return view('bookinglist', compact('data'));
        return response()->json([
            $data
        ], 201);
    }

    public function approved($id)
    {
        $data = booking::find($id);
        $data->status = 'approved';
        $data->save();
        return response()->json([
            'status' => $data->status
        ], 201);
    }

    public function canceled($id)
    {
        $data = booking::find($id);
        $data->status = 'canceled';
        $data->save();
        return response()->json([
            'status' => $data->status
        ], 201);
    }

    public function linkmeet($id)
    {
        return view('linkmeet');
    }
}
