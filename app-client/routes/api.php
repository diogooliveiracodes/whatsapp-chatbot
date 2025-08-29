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

// Public webhook for WhatsApp (multi-tenant by company and unit)
Route::get('/whatsapp/webhook/{company}/{unit}', [WhatsappWebhookController::class, 'verify'])->name('api.whatsapp.webhook.verify');
Route::post('/whatsapp/webhook/{company}/{unit}', [WhatsappWebhookController::class, '__invoke'])->name('api.whatsapp.webhook');
