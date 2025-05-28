@extends('layouts.app') 
@section('content')

<div class="px-4 py-10 sm:ml-2">
    <h1 class="text-3xl font-bold mb-6 text-black">Rekomendasi Dosis Pupuk</h1>

    {{-- Form Input Luas Lahan --}}
    <form action="{{ route('rekomendasi') }}" method="GET" class="mb-8 max-w-md bg-white p-6 rounded-lg border border-grey-300">
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
            Hitung Dosis Pupuk
        </button>
    </form>

    {{-- Hasil Sensor dan Rekomendasi --}}
    @if ($hasil)
    
    {{-- Dosis Pupuk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="flame" class="w-5 h-5 text-green-600"></i>
            <h3 class="font-semibold text-green-700 text-lg">Urea</h3>
        </div>
        <p class="text-3xl font-bold text-black">{{ $dosisPupuk['urea'] }} <span class="text-sm font-normal">kg</span></p>
        <p class="text-xs text-gray-500 mt-1">Setara untuk {{ $luasMeter }} m²</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="zap" class="w-5 h-5 text-blue-600"></i>
            <h3 class="font-semibold text-blue-700 text-lg">ZA</h3>
        </div>
        <p class="text-3xl font-bold text-black">{{ $dosisPupuk['za'] }} <span class="text-sm font-normal">kg</span></p>
        <p class="text-xs text-gray-500 mt-1">Setara untuk {{ $luasMeter }} m²</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="test-tube" class="w-5 h-5 text-purple-600"></i>
            <h3 class="font-semibold text-purple-700 text-lg">SP-36</h3>
        </div>
        <p class="text-3xl font-bold text-black">{{ $dosisPupuk['sp36'] }} <span class="text-sm font-normal">kg</span></p>
        <p class="text-xs text-gray-500 mt-1">Setara untuk {{ $luasMeter }} m²</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="droplet" class="w-5 h-5 text-red-600"></i>
            <h3 class="font-semibold text-red-700 text-lg">KCl</h3>
        </div>
        <p class="text-3xl font-bold text-black">{{ $dosisPupuk['kcl'] }} <span class="text-sm font-normal">kg</span></p>
        <p class="text-xs text-gray-500 mt-1">Setara untuk {{ $luasMeter }} m²</p>
    </div>
</div>


    {{-- Kartu Sensor dan Rekomendasi Total --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="wind" class="w-5 h-5 text-yellow-500"></i>
            <h2 class="text-md font-semibold text-black">Gas Metana (CH₄)</h2>
        </div>
        <p class="text-4xl font-bold text-black mt-2">{{ $sensor->gas_metana }} %</p>
        <p class="text-xs text-gray-500 mt-2">Terakhir diperbarui: {{ $sensor->created_at }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="thermometer" class="w-5 h-5 text-blue-500"></i>
            <h2 class="text-md font-semibold text-black">Suhu</h2>
        </div>
        <p class="text-4xl font-bold text-black mt-2">{{ $sensor->suhu }} °C</p>
        <p class="text-xs text-gray-500 mt-2">Terakhir diperbarui: {{ $sensor->created_at }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="droplets" class="w-5 h-5 text-blue-600"></i>
            <h2 class="text-md font-semibold text-black">Kelembapan Tanah</h2>
        </div>
        <p class="text-4xl font-bold text-black mt-2">{{ $sensor->kelembapan_tanah }} %</p>
        <p class="text-xs text-gray-500 mt-2">Terakhir diperbarui: {{ $sensor->created_at }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-300">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <h2 class="text-md font-semibold text-emerald-600">Dosis Pupuk Direkomendasikan</h2>
        </div>
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
