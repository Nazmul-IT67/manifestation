<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DynamicPageController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\UserPermissionController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::controller(SystemSettingController::class)->group(function () {
    Route::get('/system-setting', 'index')->name('system.index');
    Route::post('/system-setting', 'update')->name('system.update');
});

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'showProfile')->name('profile.setting');
    Route::post('/update-profile', 'UpdateProfile')->name('update.profile');
    Route::post('/update-profile-password', 'UpdatePassword')->name('update.Password');
});

Route::controller(SocialMediaController::class)->group(function () {
    Route::get('/social-media', 'index')->name('social.index');
    Route::post('/social-media', 'update')->name('social.update');
    Route::delete('/social-media/{id}', 'destroy')->name('social.delete');
});

Route::controller(DynamicPageController::class)->group(function () {
    Route::get('/dynamic-page', 'index')->name('dynamic_page.index');
    Route::get('/dynamic-page/create', 'create')->name('dynamic_page.create');
    Route::post('/dynamic-page/store', 'store')->name('dynamic_page.store');
    Route::get('/dynamic-page/edit/{id}', 'edit')->name('dynamic_page.edit');
    Route::post('/dynamic-page/update/{id}', 'update')->name('dynamic_page.update');
    Route::get('/dynamic-page/status/{id}', 'status')->name('dynamic_page.status');
    Route::delete('/dynamic-page/destroy/{id}', 'destroy')->name('dynamic_page.destroy');
});

Route::prefix('/user')->controller(UserController::class)->group(function () {
    Route::get('/index', 'index')->name('user.index');
    Route::get('/create', 'create')->name('user.create');
    Route::get('/edit/{id}', 'edit')->name('user.edit');
    Route::post('/store', 'store')->name('user.store');
    Route::post('/update/{id}', 'update')->name('user.update');
    Route::delete('/destroy/{id}', 'destroy')->name('user.destroy');
});

Route::prefix('/permissions')->controller(UserPermissionController::class)->group(function () {
    Route::get('/index', 'index')->name('permissions.index');
    Route::get('/create', 'create')->name('permissions.create');
    Route::get('/edit/{id}', 'edit')->name('permissions.edit');
    Route::post('/store', 'store')->name('permissions.store');
    Route::post('/update/{id}', 'update')->name('permissions.update');
    Route::delete('/destroy/{id}', 'destroy')->name('permissions.destroy');
});
