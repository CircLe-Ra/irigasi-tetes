<?php

use App\Http\Controllers\RelayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/relays/{device}', [RelayController::class, 'get']);
Route::post('/relays/{device}/channel/{channel}', [RelayController::class, 'set']);
Route::get('/soils/{device}', [RelayController::class, 'show']);
Route::get('/soils/{device}/data', [RelayController::class, 'updateSensor']);

