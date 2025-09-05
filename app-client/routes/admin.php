<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::middleware('admin')->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');

        Route::get('/units/by-company/{companyId}', [AdminController::class, 'getUnitsByCompany'])->name('admin.units.by-company');
        Route::patch('/company/deactivate', [AdminController::class, 'deactivateCompany'])->name('admin.company.deactivate');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [AdminController::class, 'users'])->name('admin.users.index');
            Route::get('/create', [AdminController::class, 'createUser'])->name('admin.users.create');
            Route::post('/', [AdminController::class, 'storeUser'])->name('admin.users.store');
            Route::get('/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
            Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
            Route::put('/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
            Route::patch('/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');
        });

        Route::group(['prefix' => 'companies'], function () {
            Route::get('/', [AdminController::class, 'indexCompanies'])->name('admin.companies.index');
            Route::get('/{company}/edit', [AdminController::class, 'editCompany'])->name('admin.companies.edit');
            Route::put('/{company}', [AdminController::class, 'updateCompany'])->name('admin.companies.update');
            Route::put('/{company}/settings', [AdminController::class, 'updateCompanySettings'])->name('admin.companies.settings.update');
            Route::patch('/{company}/deactivate', [AdminController::class, 'deactivateCompany'])->name('admin.companies.deactivate');
        });

        Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');

        // Admin Profile Routes
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
            Route::patch('/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
            Route::put('/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');
            Route::delete('/destroy', [AdminController::class, 'destroyProfile'])->name('admin.profile.destroy');
        });
    });
});
