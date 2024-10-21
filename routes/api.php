<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;

Route::middleware('api')->group(function () {
    Route::post('/jobs', [JobController::class, 'create']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
});
