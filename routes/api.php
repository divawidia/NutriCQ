<?php

use App\Http\Controllers\API\AuthController;
<<<<<<< HEAD
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\API\RegisterController;
=======
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\GoalController;
>>>>>>> 211a2e07248d6aa603939b82586614ac19609bbb
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
Route::get('/register', [RegisterController::class, 'register']);
Route::post('/register', [RegisterController::class, 'store']);

// User Login Route
Route::get('/login', [AuthController::class, 'indexUser']);
Route::post('/login/authenticate', [AuthController::class, 'loginUser']);

// Admin Login Route
Route::get('/login/admin', [AuthController::class, 'indexAdmin']);
Route::post('/login/admin/authenticate', [AuthController::class, 'loginAdmin']);

Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
    // Booking Route
    Route::post('/booking', [HomeController::class, 'store']);
    Route::get('/mybooking', [HomeController::class, 'my_booking']);
    Route::delete('/mybooking/{id}', [HomeController::class, 'cancel_booking']);

    //Goal Route
    Route::get('/goal', [GoalController::class, 'index']);
    Route::get('/goal/{id}', [GoalController::class, 'show']);
    Route::post('/goal', [GoalController::class, 'store']);
    Route::put('/goal/{id}', [GoalController::class, 'update']);
    Route::delete('/goal/{id}', [GoalController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:doctor']], function () {
    Route::get('/dashboard/doctor', [DoctorController::class, 'index']);

    // Booking Route
    Route::get('/bookinglist', [DoctorController::class, 'bookinglist']);
    Route::put('bookinglist/{id}/approved', [DoctorController::class, 'approved']);
    Route::put('bookinglist/{id}/canceled', [DoctorController::class, 'canceled']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('/dashboard/admin', [UserController::class, 'index']);
});

<<<<<<< HEAD
//Logout Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
=======
Route::get('/foods', [FoodController::class, 'search'])->name('foods.search');
>>>>>>> 211a2e07248d6aa603939b82586614ac19609bbb
