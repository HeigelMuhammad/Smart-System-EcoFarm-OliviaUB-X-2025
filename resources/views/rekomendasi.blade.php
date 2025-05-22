@extends('layouts.app') 
@section('content')
<x-sidebar />

<div class="p-4 pt-10 sm:ml-64">
    <h1 class="text-3xl font-bold mb-6 text-green-800">Rekomendasi Dosis Pupuk</h1>

    {{-- Form Input Luas Lahan --}}
    <form action="{{ route('rekomendasi') }}" method="GET" class="mb-8 max-w-md bg-white p-6 rounded-lg border border-green-400">
        <label for="luas_lahan" class="block mb-2 font-semibold text-gray-700">Masukkan Luas Lahan (Meter Persegi):</label>
        <input 
            type="number" 
            name="luas_lahan" 
            id="luas_lahan" 
            step="0.01" 
            min="0.01" 
            required
            value="{{ request('luas_lahan') }}"
            class="border border-gray-300 p-2 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-green-400"
        >
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full">
            Hitung Rekomendasi
        </button>
    </form>

    {{-- Hasil Sensor dan Rekomendasi --}}
    @if ($hasil)
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Kartu Sensor --}}
    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <h2 class="text-md font-semibold text-black">Gas Metana (CH₄)</h2>
        <p class="text-4xl font-bold text-black mt-2">{{ $sensor->gas_metana }} %</p>
        <p class="text-xs text-gray-500 mt-2">Terakhir diperbarui: {{ $sensor->created_at }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <h2 class="text-md font-semibold text-black">Suhu</h2>
        <p class="text-4xl font-bold text-black mt-2">{{ $sensor->suhu }} °C</p>
        <p class="text-xs text-gray-500 mt-2">Terakhir diperbarui: {{ $sensor->created_at }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <h2 class="text-md font-semibold text-black">Kelembapan Tanah</h2>
        <p class="text-4xl font-bold text-black mt-2">{{ $sensor->kelembapan_tanah }} %</p>
        <p class="text-xs text-gray-500 mt-2">Terakhir diperbarui: {{ $sensor->created_at }}</p>
    </div>

    {{-- Koefisien Penyesuaian --}}
    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <h2 class="text-md font-semibold text-purple-600">Koefisien Penyesuaian (Kp)</h2>
        <p class="text-4xl font-bold text-purple-700 mt-2">{{ $hasil['Kp'] }}</p>
        <p class="text-xs text-gray-500 mt-2">Dihitung otomatis berdasarkan kondisi lahan</p>
    </div>

    {{-- Dosis Pupuk Direkomendasikan --}}
    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <h2 class="text-md font-semibold text-emerald-600">Dosis Pupuk Direkomendasikan</h2>
        <p class="text-4xl font-bold text-emerald-700 mt-2">{{ $hasil['total_pupuk_kg'] * $luasMeter }} kg</p>
        <p class="text-xs text-gray-500 mt-2">Dosis optimal berdasarkan data sensor</p>
    </div>
</div>


    {{-- Rekomendasi Pupuk --}}
    <div class="mt-8 bg-yellow-50 p-6 rounded border-l-4 border-yellow-600">
        <h2 class="font-bold text-yellow-800 text-lg mb-2">Rekomendasi Jenis Pupuk & Perlakuan</h2>
        <ul class="list-disc ml-6 text-sm text-gray-700 space-y-1">
            @foreach ($rekomendasiPupuk as $item)
                <li>{!! $item !!}</li>
            @endforeach
        </ul>
    </div>

    {{-- Ringkasan Akhir --}}
    <div class="mt-8 bg-green-50 p-6 rounded border-l-4 border-green-600">
        <h2 class="font-bold text-green-800 text-lg mb-2">Ringkasan Luas Lahan & Total Pupuk</h2>
        <p><strong>Luas Lahan:</strong> {{ $luasMeter }} m2</p>
        <p><strong>Total Dosis Pupuk:</strong> {{ $hasil['total_pupuk_kg'] * $luasMeter }} kg</p>
    </div>

    @else
    <div class="text-red-600 mt-6 text-center bg-red-50 p-4 rounded border border-red-200">
        <p>Data sensor tidak tersedia. Pastikan sensor sudah mengirimkan data terbaru.</p>
    </div>
    @endif
</div>
@endsection
