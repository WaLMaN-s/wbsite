<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReservationAdminController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/reservasi', [ReservationController::class, 'index'])->name('reservasi.index');
Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservasi.store');
Route::get('/reservasi/sukses/{bookingCode}', [ReservationController::class, 'success'])->name('reservasi.success');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Reservations management
        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('/', [ReservationAdminController::class, 'index'])->name('index');
            Route::get('/{id}', [ReservationAdminController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ReservationAdminController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ReservationAdminController::class, 'update'])->name('update');
            Route::delete('/{id}', [ReservationAdminController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/status', [ReservationAdminController::class, 'updateStatus'])->name('update-status');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/update', [SettingController::class, 'update'])->name('update');
        });
    });
});
