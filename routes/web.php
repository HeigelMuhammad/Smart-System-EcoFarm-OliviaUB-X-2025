<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\WifiSettingController;
use App\Models\SensorData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

    Route::view('/guest', 'guest')->name('guest');

    Route::get('/', function () {
        if (Auth::check()) {
            return redirect()->route('monitoring');
        }
        return view('guest');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('monitoring');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/monitoring', function () {
        $data = SensorData::latest()->get();
        return view('monitoring', compact('data'));
    })->name('monitoring');

    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi');
});

require __DIR__.'/auth.php';
