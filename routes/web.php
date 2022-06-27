<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function (){
    return view('register');
});

Route::post('/register', [UserController::class, 'store']);

Route::resource('food', FoodController::class);

//Route::get('/food-diary', [FoodController::class, 'index']);

// Route::post('/food-diary', [])