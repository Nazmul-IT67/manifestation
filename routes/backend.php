<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Web\Backend\UserController;
use App\Http\Controllers\Admin\DynamicPageController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Web\Backend\JournalController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Web\Backend\ContentsController;
use App\Http\Controllers\Web\Backend\CategoriesController;
use App\Http\Controllers\Web\Backend\JournalTypeController;

// ------------------------- Dashboard --------------------------
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

// ------------------------- System Setting ---------------------
Route::controller(SystemSettingController::class)->group(function () {
    Route::get('/system-setting', 'index')->name('admin.system.index');
    Route::post('/system-setting', 'update')->name('admin.system.update');
});

// ------------------------- Profile ----------------------------
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'showProfile')->name('admin.profile.setting');
    Route::post('/update-profile', 'UpdateProfile')->name('admin.update.profile');
    Route::post('/update-profile-password', 'UpdatePassword')->name('admin.update.password');
});

// ------------------------- Social Media -----------------------
Route::controller(SocialMediaController::class)->group(function () {
    Route::get('/social-media', 'index')->name('admin.social.index');
    Route::post('/social-media', 'update')->name('admin.social.update');
    Route::delete('/social-media/{id}', 'destroy')->name('admin.social.delete');
});

// ------------------------- Dynamic Pages ----------------------
Route::controller(DynamicPageController::class)->group(function () {
    Route::get('/dynamic-page', 'index')->name('admin.dynamic_page.index');
    Route::get('/dynamic-page/create', 'create')->name('admin.dynamic_page.create');
    Route::post('/dynamic-page/store', 'store')->name('admin.dynamic_page.store');
    Route::get('/dynamic-page/edit/{id}', 'edit')->name('admin.dynamic_page.edit');
    Route::post('/dynamic-page/update/{id}', 'update')->name('admin.dynamic_page.update');
    Route::get('/dynamic-page/status/{id}', 'status')->name('admin.dynamic_page.status');
    Route::delete('/dynamic-page/destroy/{id}', 'destroy')->name('admin.dynamic_page.destroy');
});

// ------------------------- Users ------------------------------
Route::resource('users', UserController::class);
Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.role');
Route::patch('users/{user}/account-status', [UserController::class, 'updateStatus'])->name('admin.users.status');

// ------------------------- categories -------------------------
Route::resource('categories', CategoriesController::class);
Route::patch('categories/{category}/status', [CategoriesController::class, 'updateStatus'])->name('admin.categories.status');

// ------------------------- Content ----------------------------
Route::resource('contents', ContentsController::class);

// ------------------------- journal ----------------------------
Route::resource('journal', JournalController::class);
Route::patch('journal/status/{id}', [JournalController::class, 'updateStatus'])->name('journal.status');

// ------------------------- journal Type -----------------------
Route::resource('journal-type', JournalTypeController::class);
Route::patch('journal-type/status/{id}', [JournalTypeController::class, 'updateStatus'])->name('journal-type.status');