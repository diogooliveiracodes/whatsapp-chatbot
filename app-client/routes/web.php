<?php

use App\Http\Controllers\ChatSessionController;
use App\Http\Controllers\UnitSettingsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleBlockController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitServiceTypeController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScheduleLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

// Public schedule link (no auth)
Route::prefix('{company}/schedule-link')->group(function () {
    Route::get('/', [ScheduleLinkController::class, 'index'])->name('schedule-link.index');
    Route::get('/{unit}', [ScheduleLinkController::class, 'show'])->name('schedule-link.show');
    Route::get('/{unit}/available-days', [ScheduleLinkController::class, 'availableDays'])->name('schedule-link.available-days');
    Route::get('/{unit}/available-times', [ScheduleLinkController::class, 'availableTimes'])->name('schedule-link.available-times');
    Route::post('/{unit}', [ScheduleLinkController::class, 'store'])->name('schedule-link.store');
    Route::get('/{unit}/success', [ScheduleLinkController::class, 'success'])->name('schedule-link.success');
});

Route::middleware('auth', 'company.active', 'subscription.active', 'user.active')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show');
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
        Route::get('/deactivated', [UnitController::class, 'deactivated'])->name('units.deactivated');
        Route::get('/{unit}', [UnitController::class, 'show'])->name('units.show');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
        Route::put('/{unit}', [UnitController::class, 'update'])->name('units.update');
        Route::patch('/{unit}', [UnitController::class, 'deactivate'])->name('units.deactivate');
        Route::post('/', [UnitController::class, 'store'])->name('units.store');
        Route::patch('/{unit}/activate', [UnitController::class, 'activate'])->name('units.activate');
    });

    Route::group(['prefix' => 'unitSettings'], function () {
        Route::get('/', [UnitSettingsController::class, 'index'])->name('unitSettings.index');
        Route::post('/', [UnitSettingsController::class, 'store'])->name('unitSettings.store');
        Route::get('/create', [UnitSettingsController::class, 'create'])->name('unitSettings.create');
        Route::get('/{unitSettings}', [UnitSettingsController::class, 'show'])->name('unitSettings.show');
        Route::get('/{unitSettings}/edit', [UnitSettingsController::class, 'edit'])->name('unitSettings.edit');
        Route::put('/{unitSettings}', [UnitSettingsController::class, 'update'])->name('unitSettings.update');
        Route::delete('/{unitSettings}', [UnitSettingsController::class, 'destroy'])->name('unitSettings.destroy');
    });

    Route::group(['prefix' => 'unitServiceTypes'], function () {
        Route::get('/', [UnitServiceTypeController::class, 'index'])->name('unitServiceTypes.index');
        Route::get('/create', [UnitServiceTypeController::class, 'create'])->name('unitServiceTypes.create');
        Route::get('/deactivated', [UnitServiceTypeController::class, 'deactivated'])->name('unitServiceTypes.deactivated');
        Route::post('/', [UnitServiceTypeController::class, 'store'])->name('unitServiceTypes.store');
        Route::get('/{unitServiceType}', [UnitServiceTypeController::class, 'show'])->name('unitServiceTypes.show');
        Route::get('/{unitServiceType}/edit', [UnitServiceTypeController::class, 'edit'])->name('unitServiceTypes.edit');
        Route::put('/{unitServiceType}', [UnitServiceTypeController::class, 'update'])->name('unitServiceTypes.update');
        Route::patch('/{unitServiceType}', [UnitServiceTypeController::class, 'deactivate'])->name('unitServiceTypes.deactivate');
        Route::patch('/{unitServiceType}/activate', [UnitServiceTypeController::class, 'activate'])->name('unitServiceTypes.activate');
    });

    Route::get('/schedules', [ScheduleController::class, 'weekly'])->name('schedules.weekly');
    Route::get('/schedules/daily', [ScheduleController::class, 'daily'])->name('schedules.daily');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::group(['prefix' => 'schedule-blocks'], function () {
        Route::get('/', [ScheduleBlockController::class, 'index'])->name('schedule-blocks.index');
        Route::get('/create', [ScheduleBlockController::class, 'create'])->name('schedule-blocks.create');
        Route::post('/', [ScheduleBlockController::class, 'store'])->name('schedule-blocks.store');
        Route::get('/{scheduleBlock}/edit', [ScheduleBlockController::class, 'edit'])->name('schedule-blocks.edit');
        Route::put('/{scheduleBlock}', [ScheduleBlockController::class, 'update'])->name('schedule-blocks.update');
        Route::delete('/{scheduleBlock}', [ScheduleBlockController::class, 'destroy'])->name('schedule-blocks.destroy');
    });

    Route::group(['prefix' => 'signature'], function () {
        Route::get('/', [SignatureController::class, 'index'])->name('signature.index');
        Route::get('/{signature}/payment', [SignatureController::class, 'payment'])->name('signature.payment');
        Route::post('/{signature}/generate-pix', [SignatureController::class, 'generatePayment'])->name('signature.generate-pix');
        Route::post('/{signature}/get-pix-code', [SignatureController::class, 'getPixCode'])->name('signature.get-pix-code');
        Route::post('/{signature}/check-payment-status', [SignatureController::class, 'checkPaymentStatus'])->name('signature.check-payment-status');
    });

    Route::group(['prefix' => 'payments'], function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    });

    Route::group(['prefix' => 'users', 'middleware' => 'owner'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/deactivated', [UserController::class, 'deactivated'])->name('users.deactivated');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::put('/{user}/password', [UserController::class, 'updatePassword'])->name('users.update-password');
        Route::patch('/{user}', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::patch('/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    });
});


require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
