<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('reset-password/{token}', [PasswordResetController::class, 'reset']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user',[ProfileController::class,'getUser']);
    Route::patch('/update-profile', [ProfileController::class, 'update']);
    Route::patch('/update-password', [ProfileController::class, 'update_password']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
