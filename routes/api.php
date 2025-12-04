<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserActivityController;

// Website Stay Email Route - Using web auth instead of sanctum
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/user/send-help-email', [UserActivityController::class, 'sendHelpEmail'])
         ->name('api.send-help-email');
});