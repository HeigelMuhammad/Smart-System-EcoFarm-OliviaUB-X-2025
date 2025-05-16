<?php

use Illuminate\Support\Facades\Route;
use App\Models\SensorData;
use App\Http\Controllers\RekomendasiController;

Route::get('/monitoring', function () {
    $data = SensorData::latest()->get();
    return view('monitoring', compact('data'));
})->name('monitoring');
Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi');


