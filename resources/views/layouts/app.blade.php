<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Padi Rendah Karbon</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="min-h-screen">
    <x-sidebar />


        {{-- Konten utama --}}
        <main class="p-4">
            @yield('content')
        </main>
    </div>

    {{-- Script JS jika ada --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
