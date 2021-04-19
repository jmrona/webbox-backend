<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\HobbyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/* Public routes */
// Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

/* Private routes */
Route::group(['middleware' => ['auth:sanctum']], function() {
    //Auth
    Route::get('/logout', [AuthController::class, 'logout']);

    // Users
    Route::get('/user', [UserController::class, 'index']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);

    // Hobbies
    Route::get('/hobby', [HobbyController::class, 'index']);
    Route::post('/hobby', [HobbyController::class, 'store']);
    Route::put('/hobby/{id}', [HobbyController::class, 'update']);
    Route::delete('/hobby/{id}', [HobbyController::class, 'destroy']);

    // Avatar
    Route::post('/avatar', [AvatarController::class, 'store']);
    Route::delete('/avatar', [AvatarController::class, 'destroy']);
});
