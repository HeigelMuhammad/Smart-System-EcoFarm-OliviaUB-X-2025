<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/lucide@latest"></script>

    <title>{{ config('app.name', 'Eco Farm') }}</title>

    <!-- Logo di tab browser -->
    <link rel="icon" type="image/png" href="{{ asset('cleanx32.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <!-- Tombol Toggle Sidebar (muncul di layar kecil) -->
    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
        class="fixed top-2 left-2 z-50 inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>
    </div>
    <!-- Sidebar -->
    <x-sidebar />
    <!-- Konten utama -->
    <div class="p-4 sm:ml-64"> {{-- tambah pt-20 agar konten tidak tertutup navbar --}}
        @isset($header)
            <header class="bg-white shadow rounded mb-4">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Lucide icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

    <!-- Script toggle sidebar -->
    <script>
        const toggleButton = document.querySelector('[data-drawer-toggle="logo-sidebar"]');
        const sidebar = document.getElementById('logo-sidebar');

        toggleButton?.addEventListener('click', () => {
            sidebar?.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>
