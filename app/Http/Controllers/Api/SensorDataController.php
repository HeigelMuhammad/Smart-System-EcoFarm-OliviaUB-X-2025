<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    public function store(Request $request)
{
    //\Log::info('Data yang diterima: ', $request->all());

    $validated = $request->validate([
        'kelembapan_tanah' => 'required|numeric',
        'suhu' => 'required|numeric',
        'gas_karbon' => 'required|numeric',
        'gas_metana' => 'required|numeric'
    ]);

    $sensor = SensorData::create([ 
        'kelembapan_tanah' => $validated['kelembapan_tanah'],
        'suhu' => $validated['suhu'],
        'gas_karbon' => $validated['gas_karbon'],
        'gas_metana' => $validated['gas_metana']
    ]);

    return response()->json([
        'message' => 'Data berhasil disimpan',
        'data' => $sensor
    ]);
}


public function index()
{
    $data = SensorData::orderBy('created_at', 'desc')->take(20)->get();
    return response()->json($data); //Kembalikan JSON untuk ESP32
}

public function getAll()
{
    return response()->json(SensorData::orderBy('created_at')->get());
}
public function latest()
{
    $latest = SensorData::latest()->first();
    return response()->json($latest);
}



}
