<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\Api\ContactControllerApi;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Route::apiResource('contacts', ContactController::class)->names('contact');
    
});
Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactControllerApi::class, 'index']);
    Route::post('/', [ContactControllerApi::class, 'store']);
    Route::put('/{contact}', [ContactControllerApi::class, 'update']);
    Route::delete('/{contact}', [ContactControllerApi::class, 'destroy']);
});