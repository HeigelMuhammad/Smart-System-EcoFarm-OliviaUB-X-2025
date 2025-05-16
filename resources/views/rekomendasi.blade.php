@extends('layouts.app') 
@section('content')
<x-sidebar />

<div class="p-4 pt-10 sm:ml-64">
    <h1 class="text-2xl font-bold mb-6">Rekomendasi Dosis Pupuk</h1>
    <form action="{{ route('rekomendasi') }}" method="GET" class="mb-6 max-w-md">
    <label for="luas_lahan" class="block mb-2 font-semibold">Masukkan Luas Lahan (ha):</label>
    <input type="number" name="luas_lahan" id="luas_lahan" step="0.1" min="0.1" required
           value="{{ request('luas_lahan') }}"
           class="border border-gray-300 p-2 rounded w-full mb-4">
    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        Hitung Dosis
    </button>
</form>


    @if ($hasil)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Gas Metana -->
            <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
                <h2 class="text-md font-semibold text-gray-500">Gas Metana (CH₄)</h2>
                <p class="text-4xl font-bold text-black mt-2">{{ $sensor->gas_metana }} mg/m²/hari</p>
                <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $sensor->created_at }}</p>
            </div>

            <!-- Gas Karbon Monoksida -->
            <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
                <h2 class="text-md font-semibold text-gray-500">Gas Karbon Monoksida (CO)</h2>
                <p class="text-4xl font-bold text-black mt-2">{{ $sensor->gas_karbon }} ppm</p>
                <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $sensor->created_at }}</p>
            </div>

            <!-- Suhu -->
            <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
                <h2 class="text-md font-semibold text-gray-500">Suhu</h2>
                <p class="text-4xl font-bold text-black mt-2">{{ $sensor->suhu }} °C</p>
                <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $sensor->created_at }}</p>
            </div>

            <!-- Kelembapan Tanah -->
            <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
                <h2 class="text-md font-semibold text-gray-500">Kelembapan Tanah</h2>
                <p class="text-4xl font-bold text-black mt-2">{{ $sensor->kelembapan_tanah }}%</p>
                <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $sensor->created_at }}</p>
            </div>

            <!-- Koefisien Penyesuaian -->
            <div class="bg-white p-6 rounded-xl text-left border border-green-400">
                <h2 class="text-md font-semibold text-green-600">Koefisien Penyesuaian (Kp)</h2>
                <p class="text-4xl font-bold text-green-700 mt-2">{{ $hasil['Kp'] }}</p>
                <p class="text-xs text-gray-500 mt-2">Dihitung otomatis</p>
            </div>

            <!-- Rekomendasi Dosis Pupuk -->
            <div class="bg-white p-6 rounded-xl text-left border border-green-600">
                <h2 class="text-md font-semibold text-green-700">Dosis Pupuk Direkomendasikan</h2>
                <p class="text-4xl font-bold text-green-800 mt-2">{{ $hasil['total_pupuk'] }} ton/ha</p>
                <p class="text-xs text-gray-500 mt-2">Dosis optimal berdasarkan data sensor</p>
            </div>

            <div class="mt-8 bg-green-50 p-4 rounded border-l-4 border-green-600">
    <h2 class="font-bold text-green-800 text-lg mb-2">Informasi Luas Lahan & Total Pupuk</h2>
    <p><strong>Luas Lahan:</strong> {{ $luasLahan }} ha</p>
    <p><strong>Total Dosis Pupuk:</strong> {{ $hasil['total_pupuk'] }} ton</p>
</div>

<div class="mt-6 bg-yellow-50 p-4 rounded border-l-4 border-yellow-600">
    <h2 class="font-bold text-yellow-800 text-lg mb-2">Rekomendasi Pupuk & Perlakuan</h2>
    <ul class="list-disc ml-6 text-sm">
        @foreach ($rekomendasiPupuk as $item)
            <li>{!! $item !!}</li>
        @endforeach
    </ul>
</div>

        </div>
    @else
        <div class="text-red-600 mt-6">
            <p>Tidak ada data sensor tersedia.</p>
        </div>
    @endif
</div>
@endsection
