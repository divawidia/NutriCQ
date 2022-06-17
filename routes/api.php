<?php

use App\Http\Controllers\API\GoalController;
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

//Route::get('role', [Controller::class, 'index']);
Route::get('/goal', [GoalController::class, 'index']);
Route::get('/goal/{id}', [GoalController::class, 'show']);
Route::post('/goal', [GoalController::class, 'store']);
Route::put('/goal/{id}', [GoalController::class, 'update']);
Route::delete('/goal/{id}', [GoalController::class, 'destroy']);

