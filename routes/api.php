<?php


use App\Adapter\Http\API\Auth\Controllers\AuthenticationController;
use App\Adapter\Http\API\Auth\Controllers\LoginController;
use App\Adapter\Http\API\Auth\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
   Route::post('/register', RegisterController::class);
   Route::post('/login', LoginController::class);
   // Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword']);
    //Route::post('/reset-password', [AuthenticationController::class, 'resetPassword']);
    //Route::post('/validate-account', [AuthenticationController::class, 'validateAccount']);
});
