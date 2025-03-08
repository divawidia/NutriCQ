<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $userid = Auth::user();
        $data = new booking;
        $data->nama_user = $userid->name;
        $data->tanggal = $request->tanggal;
        $data->deskripsi = $request->deskripsi;
        $data->start_time = $request->start_time;
        $data->end_time = $request->end_time;
        $data->status = 'Waiting';
        $data->user_id = $userid->id;

        $doctor = User::hasRoles('doctor')
            ->where('id', '=', $request->input('user_dokter_id'))
            ->first();

        if ($doctor != null) {
            $data->user_dokter_id = $doctor->id;
            $data->nama_dokter = $doctor->name;
        } else {
            return response([
                'message' => "doctor id not found!"
            ], 404);
        }

        $data->save();

        return response()->json([
            'ID' => $data->id,
            'Nama User' => $data->nama_user,
            'Dokter ID' => $data->user_dokter_id,
            'Nama Dokter' => $data->nama_dokter,
            'Tanggal' => $data->tanggal,
            'Jam' => $data->start_time . "-" . $data->end_time,
            'Deskripsi' => $data->deskripsi,
            'Status' => $data->status,
            'message' => 'Booking successfull!'
        ], 201);
    }

    public function my_booking()
    {
        if (Auth::id()) {
            $userid = Auth::user()->id;
            $bookings = booking::where('user_id', $userid)->get();

            $response = [];
            if ($bookings) {
                foreach ($bookings as $list) {
                    $response[] = [
                        'ID' => $list->id,
                        'Nama User' => $list->nama_user,
                        'Dokter ID' => $list->user_dokter_id,
                        'Nama Dokter' => $list->nama_dokter,
                        'Tanggal' => $list->tanggal,
                        'Jam' => $list->start_time . "-" . $list->end_time,
                        'Deskripsi' => $list->deskripsi,
                        'Status' => $list->status,
                    ];
                }

                if ($response != null) {
                    return response([$response], 200);
                }
                return response([], 200);
            }
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

        $response = [];
        if ($bookinglist) {
            foreach ($bookinglist as $list) {
                $response[] = [
                    'ID' => $list->id,
                    'Nama User' => $list->nama_user,
                    'Tanggal' => $list->tanggal,
                    'Jam' => $list->start_time . "-" . $list->end_time,
                    'Deskripsi' => $list->deskripsi,
                    'Status' => $list->status,
                ];
            }

            if ($response != null) {
                return response([$response], 200);
            }
            return response([], 200);
        }
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

    public function rejected($id)
    {
        $data = booking::find($id);
        $data->status = 'rejected';
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
