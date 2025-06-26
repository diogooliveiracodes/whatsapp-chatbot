<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::middleware('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::group(['prefix' => 'users'], function () {

    });
    Route::group(['prefix' => 'companies'], function () {

    });
    Route::group(['prefix' => 'plans'], function () {

    });
});