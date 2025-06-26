<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::middleware('admin')->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');

        Route::get('/units/by-company/{companyId}', [AdminController::class, 'getUnitsByCompany'])->name('admin.units.by-company');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [AdminController::class, 'users'])->name('admin.users.index');
            Route::get('/create', [AdminController::class, 'createUser'])->name('admin.users.create');
            Route::post('/', [AdminController::class, 'storeUser'])->name('admin.users.store');
            Route::get('/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
            Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
            Route::put('/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
            Route::patch('/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');
        });
    });
});
