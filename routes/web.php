<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// this route for guest user
Route::middleware('guest')->group(function () {

    // Register route
    Route::get('/register', [RegisterController::class, 'register']);
    Route::post('/register', [RegisterController::class, 'store']);

    // new login route
    Route::get('/login', [LoginController::class, 'login']);
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
});

// route for user
Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {

    Route::get('/dashboard/user', [HomeController::class, 'redirect']);

    // Booking Route
    Route::get('/booking', [HomeController::class, 'booking_view']);
    Route::post('/booking', [HomeController::class, 'booking']);
    Route::get('/mybooking', [HomeController::class, 'my_booking']);
    Route::get('/cancel_booking/{id}', [HomeController::class, 'cancel_booking']);

    //Goal Route
    Route::get('/goal', [GoalController::class, 'index']);
    Route::get('/goal/{id}', [GoalController::class, 'show']);
    Route::post('/goal', [GoalController::class, 'store']);
    Route::put('/goal/{id}', [GoalController::class, 'update']);
    Route::delete('/goal/{id}', [GoalController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:doctor']], function () {
    Route::get('/dashboard/doctor', [HomeController::class, 'redirect']);
    Route::get('/bookinglist', [DoctorController::class, 'bookinglist']);
    Route::get('/approved/{id}', [DoctorController::class, 'approved']);
    Route::get('/canceled/{id}', [DoctorController::class, 'canceled']);
    Route::get('/linkmeet/{id}', [DoctorController::class, 'linkmeet']);
});

// //Logout Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/logout', [LoginController::class, 'logout']);
});
