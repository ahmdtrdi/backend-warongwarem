<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\tablesController;

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
Route::middleware('auth:api')->get('/user/reservations/{id}', [ReservationController::class, 'historyReservation']);
//Route::middleware('auth:api')->get('/tables/available', [TableController::class, 'viewAvailableTables']);
Route::middleware('auth:api')->put('/reservations/{id}/status', [ReservationController::class, 'updateStatus']);
Route::middleware('auth:api')->put('/reservations/{id}/reschedule', [ReservationController::class, 'reschedule']);
Route::middleware('auth:api')->put('/tables/{id}/assign/{reservationId}', [tablesController::class, 'assignTable']);
Route::middleware('auth:api')->put('/tables/{id}/available', [tablesController::class, 'unassignTable']);
Route::post('/auth/register', [UserController::class, 'register'])->middleware('throttle:10,1');
Route::post('/auth/login', [UserController::class, 'login'])->middleware('throttle:10,1');

Route::get('/table/all-tables', [tablesController::class, 'index']);
Route::get('tables/available', [tablesController::class, 'availableTables']);

// Routes APIs Reservations //
Route::middleware('auth:api')->group(function () {
    Route::get('/reservations/list', [ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::post('/reservations', [ReservationController::class, 'store']);
});