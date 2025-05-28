<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;
use App\Models\WifiSetting;
use Illuminate\Support\Facades\Cache;

// Contoh route awal
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('/sensor', [SensorDataController::class, 'store']);
Route::get('/sensor', [SensorDataController::class, 'index']);
Route::get('/sensor-data', [SensorDataController::class, 'getAll']);
Route::get('/sensor-data/latest', [SensorDataController::class, 'latest']);

Route::post('/pompa-status', function (Request $request) {
    Cache::put('pompa_status', $request->status, now()->addMinutes(10)); // simpan status sementara
    return response()->json(['message' => 'Status updated']);
});

Route::get('/pompa-status', function () {
    $status = Cache::get('pompa_status', 'Tidak Diketahui');
    return response()->json(['status' => $status]);
});


