<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\UserSubscriptionController;

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
Route::controller(SocialLoginController::class)->prefix('social-login')->group(function () {
    Route::post('google', 'googleLogin');
    Route::post('apple', 'appleLogin');
});

// Users API
Route::middleware(['auth:sanctum', 'track.activity'])->group(function () {
    Route::get('users/index', [UserController::class, 'Index']);
    Route::post('users/logout', [UserController::class, 'Logout']);
    Route::post('/users/update', [UserController::class, 'Update']);
    Route::post('users/change-password', [UserController::class, 'changePassword']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    // Content API
    Route::controller(ContentController::class)->prefix('contents')->group(function () {
        Route::get('/index', 'index');
        Route::get('/show', 'show');
        Route::post('/store', 'store');
        Route::post('/update', 'update');
        Route::delete('/delete', 'destroy');
    });

    // Category API
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/index', 'index');
        Route::get('/show', 'show');
        Route::post('/store', 'store');
        Route::post('/update', 'update');
        Route::delete('/delete', 'destroy');
    });

    // SubscriptionPlan API
    Route::controller(SubscriptionController::class)->prefix('subscription')->group(function () {
        Route::get('/index', 'index');
        Route::get('/show', 'show');
        Route::post('/store', 'store');
        Route::post('/update', 'update');
    });

    // Subscription API
    Route::controller(UserSubscriptionController::class)->prefix('subscription')->group(function () {
        Route::get('/current', 'currentSubscription');
        Route::post('/purchase', 'purchaseSubscription');
    });

});