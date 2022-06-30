<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\FoodDiaryController;
use App\Http\Controllers\API\GoalController;
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
Route::post('/register', [AuthController::class, 'register']);

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

    //Goal Route
    Route::get('/goal', [GoalController::class, 'index']);
    Route::get('/goal/{id}', [GoalController::class, 'show']);
    Route::post('/goal', [GoalController::class, 'store']);
    Route::put('/goal/{id}', [GoalController::class, 'update']);
    Route::delete('/goal/{id}', [GoalController::class, 'destroy']);

    Route::apiResource('food-diary', FoodDiaryController::class);
    Route::patch('/food-diary/{food_diary}', [FoodDiaryController::class, 'addFoodToExistingFoodDiary'])->name('food-diary.addFoodToExistingFoodDiary');
    Route::patch('/foods/{food}', [FoodController::class, 'storeCalculatedFoodToFoodDiary'])->name('foods.storeCalculatedFoodToFoodDiary');
});

Route::group(['middleware' => ['auth:sanctum', 'role:doctor']], function () {
    Route::get('/dashboard/doctor', [UserController::class, 'index']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('/dashboard/admin', [UserController::class, 'index']);
});

Route::get('/foods', [FoodController::class, 'search'])->name('foods.search');
Route::get('/foods/{food}', [FoodController::class, 'calculate'])->name('foods.calculate');
