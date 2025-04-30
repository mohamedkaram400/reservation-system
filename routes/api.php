<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ReservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login',    'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(ReservationController::class)->group(function () {
        Route::get('all-reservations', 'allReservation');
        Route::post('make-reservation', 'makeReservation');
        Route::get('upcoming-reservations', 'upcomingReservations');
        Route::post('cancel-reservation/{id}', 'cancelReservation');
    });

    Route::apiResource('services', ServiceController::class);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
