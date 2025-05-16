<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;

// Contoh route awal
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('/sensor', [SensorDataController::class, 'store']);
Route::get('/sensor', [SensorDataController::class, 'index']);
Route::get('/sensor-data', [SensorDataController::class, 'getAll']);
