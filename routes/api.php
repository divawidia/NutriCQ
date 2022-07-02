<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\API\RegisterController;
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
Route::post('/register/user', [RegisterController::class, 'register_user']);
Route::post('/register/doctor', [RegisterController::class, 'register_doctor']);

//User Login Route
Route::get('/login', [AuthController::class, 'indexUser']);
Route::post('/login/authenticate', [AuthController::class, 'loginUser']);

//Admin Login Route
Route::get('/login/admin', [AuthController::class, 'indexAdmin']);
Route::post('/login/admin/authenticate', [AuthController::class, 'loginAdmin']);

//Logout Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
    Route::get('/dashboard/user', [UserController::class, 'index']);

    // Route Booking
    Route::post('/booking', [BookingController::class, 'store']);
    Route::get('/mybooking', [BookingController::class, 'my_booking']);

    //Goal Route
    Route::get('/goal', [GoalController::class, 'index']);
    Route::get('/goal/{id}', [GoalController::class, 'show']);
    Route::post('/goal', [GoalController::class, 'store']);
    Route::put('/goal/{id}', [GoalController::class, 'update']);
    Route::delete('/goal/{id}', [GoalController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:doctor']], function () {
    Route::get('/dashboard/doctor', [UserController::class, 'index']);

    // Route for booking
    Route::get('/bookinglist', [BookingController::class, 'bookinglist']);
    Route::put('/bookinglist/{id}/approved', [BookingController::class, 'approved']);
    Route::put('/bookinglist/{id}/rejected', [BookingController::class, 'rejected']);
    Route::put('/bookinglist/{id}/done', [BookingController::class, 'done']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('/dashboard/admin', [UserController::class, 'index']);
});

Route::get('/foods', [FoodController::class, 'search'])->name('foods.search');
