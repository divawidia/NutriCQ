<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $userid = Auth::user();
        $data = new booking;
        $data->nama_user = $userid->name;
        $data->tgl_booking = $request->tgl_booking;
        $data->deskripsi_booking = $request->deskripsi_booking;
        $data->status = 'Waiting';
        $data->user_id = $userid->id;

        $doctor = User::where('role', 'doctor')
            ->where('id', '=', $request->input('user_dokter_id'))
            ->first();

        if ($doctor != null) {
            $data->user_dokter_id = $doctor->id;
        } else {
            return response([
                'message' => "doctor id not found!"
            ], 400);
        }

        $data->save();

        return response()->json([
            'Tanggal Booking' => $data->tgl_booking,
            'Deskripsi Booking' => $data->deskripsi_booking,
            'Status' => $data->status,
            'Dokter ID' => $data->user_dokter_id,
            "ALl Doktor" => $doctor,
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
            ], 200);
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

    // for doctor
    public function bookinglist()
    {
        $doctorid = Auth::user()->id;
        $bookinglist = booking::where('user_dokter_id', $doctorid)->get();

        return response()->json([
            $bookinglist
        ], 201);
    }

    public function approved($id)
    {
        // $doctorid = Auth::user()->id;
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

    public function done($id)
    {
        $data = booking::find($id);
        $data->status = 'done';
        $data->save();
        return response()->json([
            'status' => $data->status
        ], 201);
    }
}
