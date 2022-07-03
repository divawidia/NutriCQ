<?php

use App\Http\Controllers\API\AuthController;

use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\FoodCategoryController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\FoodDiaryController;
use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Register route
Route::post('/register/user', [RegisterController::class, 'register_user'])->name('register.user');
Route::post('/register/doctor', [RegisterController::class, 'register_doctor'])->name('register.doctor');

//User Login Route
Route::get('/login', [AuthController::class, 'indexUser']);
Route::post('/login/authenticate', [AuthController::class, 'login'])->name('login.user');

//Admin Login Route
Route::get('/login/admin', [AuthController::class, 'indexAdmin']);
Route::post('/login/admin/authenticate', [AuthController::class, 'login']);

//Logout Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::put('/profile/{id}', [UserController::class, 'update'])->name('profile.update');

//Get Route Review
Route::get('/doctor/reviews', [ReviewController::class, 'index'])->name('review.index');
Route::get('/doctor/{id}/reviews', [ReviewController::class, 'reviewList'])->name('review.reviewList');
Route::get('/doctor/{id}/reviewstar', [ReviewController::class, 'reviewStar'])->name('review.reviewStar');
Route::get('/doctor/{id}/review/{id_review}', [ReviewController::class, 'specificReview'])->name('review.specificReview');

//middleware for user
Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
    Route::get('/dashboard/user', [UserController::class, 'index']);

    //Route review
    Route::post('/doctor/{id}/review/add', [ReviewController::class, 'addReview'])->name('review.addReview');
    Route::patch('/doctor/{id}/review/{id_review}', [ReviewController::class, 'editReview'])->name('review.editReview');
    Route::delete('/doctor/{id}/review/{id_review}', [ReviewController::class, 'destroyReview'])->name('review.destroyReview');

    // Route Booking
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/mybooking', [BookingController::class, 'my_booking']);

    //Goal Route
    Route::get('/goal', [GoalController::class, 'index']);
    Route::get('/goal/{id}', [GoalController::class, 'show']);
    Route::post('/goal', [GoalController::class, 'store']);
    Route::put('/goal/{id}', [GoalController::class, 'update']);
    Route::delete('/goal/{id}', [GoalController::class, 'destroy']);

    Route::apiResource('food-diary', FoodDiaryController::class);
    Route::patch('/food-diary/{food_diary}', [FoodDiaryController::class, 'addFoodToExistingFoodDiary'])->name('food-diary.addFoodToExistingFoodDiary');
    Route::patch('/foods/{food}', [FoodController::class, 'storeCalculatedFoodToFoodDiary'])->name('foods.storeCalculatedFoodToFoodDiary');

    Route::put('/foods-diary/{id}/{detail_id}', [FoodController::class, 'updateFoodDiaryDetail'])->name('fooddetail.update');
    Route::delete('/foods-diary/{id}', [FoodDiaryController::class, 'destroy'])->name('food.destroy');
    Route::delete('/foods-diary/{id}/{detail_id}', [FoodController::class, 'destroy_food_detail'])->name('fooddetail.destroy');
});

//middleware for doctor
Route::group(['middleware' => ['auth:sanctum', 'role:doctor']], function () {
    Route::get('/dashboard/doctor', [UserController::class, 'index']);

    // Route for booking
    Route::get('/bookinglist', [BookingController::class, 'bookinglist']);
    Route::put('/bookinglist/{id}/approved', [BookingController::class, 'approved']);
    Route::put('/bookinglist/{id}/rejected', [BookingController::class, 'rejected']);
    Route::put('/bookinglist/{id}/done', [BookingController::class, 'done']);
});

//middleware for admin
Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('/dashboard/admin', [UserController::class, 'index']);

    Route::get('/admin/doctor-list', [UserController::class, 'doctorIndex'])->name('admin.doctorList');
    Route::get('/admin/doctor-list/{doctor}', [UserController::class, 'showDoctor'])->name('admin.showDoctor');
    Route::patch('/admin/doctor-list/{doctor}/', [UserController::class, 'updateStatusDoctor'])->name('admin.updateStatusDoctor');


    Route::apiResource('admin/foods', FoodController::class);

    Route::apiResource('admin/food-categories', FoodCategoryController::class)->except('show');
});

Route::get('/foods', [FoodController::class, 'search'])->name('foods.search');
Route::get('/foods/{food}', [FoodController::class, 'calculate'])->name('foods.calculate');
