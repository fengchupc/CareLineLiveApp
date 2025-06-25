<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ShiftController;

Route::apiResource('carers', CarerController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('shifts', ShiftController::class); 
