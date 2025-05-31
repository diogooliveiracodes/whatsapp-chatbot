<?php

use App\Http\Controllers\ChatSessionController;
use App\Http\Controllers\CompanySettingsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
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

    Route::group(
        [
            'prefix' => 'company-settings',
            'middleware' => ['auth', 'owner'],
        ],
        function () {
            Route::get('/{company_settings}', [CompanySettingsController::class, 'show'])->name('company-settings.show');
            Route::get('edit/{company_settings}', [CompanySettingsController::class, 'edit'])->name('company-settings.edit');
            Route::put('/{company_settings}', [CompanySettingsController::class, 'update'])->name('company-settings.update');
        },
    );

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('schedules/{schedule}/cancel', [ScheduleController::class, 'cancel'])->name('schedules.cancel');
});

require __DIR__ . '/auth.php';
