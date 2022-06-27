<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return 'page for doctor reviews';
    }

    public function reviewTotal(Request $request)
    {
        $ratings = Review::where('doctor_id', $request->doctor_id)->get();
        $rating_sum = Review::where('doctor_id', $request->doctor_id)->sum('total_rating');
        $rating_value = number_format($rating_sum/$ratings->count());

        $response = [
            'message' => "Total Rating Success",
            'total_rating' => $rating_value
        ];

        return response($response, 201);
    }

    public function add(Request $request)
    {
        // // $user = $request->Auth::user()->id;
        // $doctor_id = $request->doctor_id;
        // $rating_stars = $request->rating_star;

        // $doctor_check = Doctor::where('id', $doctor_id)-first();
        // if($doctor_check) {
            
        //     $verified_booking = Booking::where('bookings.user_id', Auth::user()->id)
        //         ->join('doctors', 'bookings.user_dokter_id', 'doctors.id')
        //         ->where('');
        
        // }

        
    }
}
