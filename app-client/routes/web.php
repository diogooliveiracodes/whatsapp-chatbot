<?php

use App\Http\Controllers\ChatSessionController;
use App\Http\Controllers\UnitSettingsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    Route::group(['prefix' => 'chatSessions'], function () {
        Route::get('/', [ChatSessionController::class, 'index'])->name('chatSessions.index');
        Route::get('/{channel}', [ChatSessionController::class, 'show'])->name('chatSessions.show');
        Route::post('/store-message', [ChatSessionController::class, 'storeMessage'])->name('chatSessions.storeMessage');
        //        Route::delete('/{session}', [ChatSessionController::class, 'destroy'])->name('chatSessions.destroy');
    });

    Route::group(['prefix' => 'units'], function () {
        Route::get('/', [UnitController::class, 'index'])->name('units.index');
        Route::get('/create', [UnitController::class, 'create'])->name('units.create');
        Route::get('/{unit}', [UnitController::class, 'show'])->name('units.show');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
        Route::put('/{unit}', [UnitController::class, 'update'])->name('units.update');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
        Route::post('/', [UnitController::class, 'store'])->name('units.store');
    });

    Route::group(['prefix' => 'unitSettings'], function () {
        Route::post('/', [UnitSettingsController::class, 'store'])->name('unitSettings.store');
        Route::get('/create', [UnitSettingsController::class, 'create'])->name('unitSettings.create');
        Route::get('/{unitSettings}', [UnitSettingsController::class, 'show'])->name('unitSettings.show');
        Route::get('/{unitSettings}/edit', [UnitSettingsController::class, 'edit'])->name('unitSettings.edit');
        Route::put('/{unit}', [UnitSettingsController::class, 'update'])->name('unitSettings.update');
        Route::delete('/{unitSettings}', [UnitSettingsController::class, 'destroy'])->name('unitSettings.destroy');
    });


    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('schedules/{schedule}/cancel', [ScheduleController::class, 'cancel'])->name('schedules.cancel');
});

require __DIR__ . '/auth.php';
