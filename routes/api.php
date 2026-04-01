<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenseController;

Route::middleware('throttle:30,1')->group(function () {
    Route::post('/license/validate', [LicenseController::class, 'validate']);
});
