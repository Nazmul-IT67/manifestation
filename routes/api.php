<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\SocialLoginController;
use App\Http\Controllers\Api\Auth\RegisterController;

// RegisterUser API
Route::controller(RegisterController::class)->prefix('users')->group(function () {
    Route::post('/register', 'Register');
    Route::post('/otp-verify', 'verifyOTP');
    Route::post('/resend-otp', 'resendOTP');
});

// LoginUser API
Route::controller(LoginController::class)->prefix('users')->group(function () {
    Route::post('/login', 'Login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});

// SocialLogin API
Route::post('social-login', [SocialLoginController::class, 'login']);

// Secure Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    
    // UserController
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/index', 'Index');
        Route::post('/update', 'Update');
        Route::post('/logout', 'Logout');
        Route::post('/change-password', 'changePassword');
    });

});