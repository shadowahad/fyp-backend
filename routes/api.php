<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HelperController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

require __DIR__ . '/apiauth.php';



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/import', [AnalyticController::class, 'import']);
Route::get('/file', [FileController::class, 'index']);
Route::get('/analytics', [AnalyticController::class, 'getData']);

//Helper API
Route::post('/register', [HelperController::class, 'registerUser']);
Route::post('/forgetEmail', [HelperController::class, 'forgetEmail']);
Route::post('/resetPassword', [HelperController::class, 'resetPassword']);

// User API
Route::get('/user-get', [UserController::class, 'index']);
Route::delete('/user-delete/{id}', [UserController::class, 'delete']);
Route::post('/user-show/{id}', [UserController::class, 'show']);
Route::put('/user-update/{id}', [UserController::class, 'update']);

//Dashboard
Route::get('/dashboard', [DashboardController::class, 'dashboard']);