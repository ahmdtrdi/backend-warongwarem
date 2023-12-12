vv<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes APIs with middleware secure //
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/user/reservations', [ReservationController::class, 'userReservations']);
Route::post('/auth/register', [UserController::class, 'register'])->middleware('throttle:10,1');
Route::post('/auth/login', [UserController::class, 'login'])->middleware('throttle:10,1');

// Routes APIs Reservations //
Route::get('/reservations/list', [ReservationController::class, 'index']);
Route::get('/reservations/{id}', [ReservationController::class, 'show']);
Route::post('/reservations', [ReservationController::class, 'store']);