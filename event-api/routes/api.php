<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\EventController;

Route::prefix('v1')->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::get('/events/category/{category}', [EventController::class, 'getEventsByCategory']);
});