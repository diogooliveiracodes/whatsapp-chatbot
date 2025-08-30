<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WhatsappWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'api'], function () {
        Route::get('/customers/search', [CustomerController::class, 'search'])->name('api.customers.search');
        Route::post('/customers', [CustomerController::class, 'store'])->name('api.customers.store');
    });
});
