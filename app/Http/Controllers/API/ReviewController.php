<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        return 'page for doctor reviews';
    }

    public function specificReview($id, $id_review)
    {
        $review = Review::find($id_review);
        if(!$review) {
            return response([
                'message' => "Data Not Found"
            ], 404);
        } else {
            $response = [
                'comment' => $review->comment,
                'total_rating' => $review->total_rating
            ];
            return response($response, 200);
        }
    }

    public function reviewList($id)
    {
        $doctor = Review::where('doctor_id', $id)->get();
        $response = [];
        if($doctor) {
            foreach($doctor as $doc) {
                $user_name = User::find($doc->user_id);
                $response[] = [
                    'user name' => $user_name->name,
                    'comment' => $doc->comment,
                    'rating' => $doc->total_rating
                ];
            }
            if($response != null) {
                return response($response, 200);
            } else {
                return response([
                    'message' => "Data Not Found"
                ], 404);
            }
        }

    }

    public function reviewStar($id)
    {
            $ratings = Review::where('doctor_id', $id)->get();

            if($ratings->count() == 0){
                return response([
                    'message' => "Data Not Found",
                ], 404);
            } else {
                $rating_sum = Review::where('doctor_id', $id)->sum('total_rating');
                $rating_value = number_format($rating_sum/$ratings->count(), 2);
        
                $doctor_name = User::find($id);
    
                $response = [
                    'message' => "Get Review Stars Success",
                    'doctor name' => $doctor_name->name,
                    'total rating' => $rating_value
                ];
    
                return response($response, 200);
            }
    }

    public function addReview(Request $request, $id)
    {
        $fields = $request->validate([
            'comment' => 'required|string',
            'total_rating' => 'required|integer'
        ]);

        $user_id = Auth::user()->id;

        $check = DB::table('bookings')
                    ->where('user_id', '=', $user_id )
                    ->where('user_dokter_id', '=', $id )
                    ->where('status','=', 'done')
                    ->first();

        if(!$check) {
            return response([
                'message' => 'Bad Request',
            ], 403);
        } else {
            $review_check = DB::table('reviews')
                                ->where('user_id', '=', $user_id)
                                ->where('doctor_id', '=', $id)
                                ->first();

            if($review_check) {
                return response([
                    'message' => 'Already reviewed',
                ], 403);
            } else {

                $data = Review::create([
                    'user_id' => $user_id,
                    'doctor_id' => $id,
                    'comment' => $fields['comment'],
                    'total_rating' => $fields['total_rating']
                ]);
    
                return response([
                    'data' => $data,
                    'message' => "Data Successfully Created"
                ], 201);
            }
        }
    }

    public function editReview(Request $request, $id, $id_review)
    {
        $fields = $request->validate([
            'comment' => 'required|string',
            'total_rating' => 'required|numeric'
        ]);

        $review = Review::find($id_review);
        if(!$review) {
            return response([
                'message' => "Data Not Found"
            ], 404);
        } else {
            if($review->comment == $fields['comment'] && $review->total_rating == $fields['total_rating']) {
                return response([
                    'message' => "No data updated"
                ], 200);
            } else {
                $review->comment = $fields['comment'];
                $review->total_rating = $fields['total_rating'];
                $review->update();
                return response([
                    'message' => "Data updated"
                ], 201);
            }

        }
    }

    public function destroyReview($id, $id_review)
    {
        $delete_check = Review::where('id', $id_review)->delete();

        if($delete_check) {
            return response([
                'message' => "Data successfully removed"
            ], 200);
        } else {
            return response([
                'message' => "Data Not Found"
            ], 404);
        }
    }
}
