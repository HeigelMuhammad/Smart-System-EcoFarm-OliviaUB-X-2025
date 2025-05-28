<!-- resources/views/guest.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang - Eco Farm</title>
    <link rel="icon" type="image/png" href="{{ asset('cleanx32.png') }}">
    <script src="https://unpkg.com/lucide@latest/dist/lucide.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font: Figtree dari Bunny.net -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Konfigurasi Tailwind untuk font Figtree -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        figtree: ['"Figtree"', 'sans-serif'],
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-white text-gray-800 font-figtree">

    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('img/clean.png') }}" class="h-6 sm:h-7" alt="Eco Farm Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap">Eco Farm</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="{{ route('register') }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 text-center">
                    Get started
                </a>
                <button data-collapse-toggle="navbar-cta" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-cta" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                    <li>
                        <a href="#" class="block py-2 px-3 md:p-0 text-white bg-green-600 rounded md:bg-transparent md:text-green-600" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-600">About</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-600">Tutorials</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero / CTA Section -->
    <section class="bg-white">
        <div class="py-12 px-4 mx-auto max-w-screen-xl text-center lg:py-20">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-tight text-gray-900 md:text-5xl lg:text-6xl">
                Eco Farm - Pantau Emisi, Jaga Bumi
            </h1>
            <p class="mb-8 text-lg font-normal text-gray-600 lg:text-xl sm:px-16 lg:px-48">
                Di sini, Anda akan mendapatkan monitoring lingkungan pertanian serta rekomendasi pemupukan yang tepat untuk mengurangi dampak lingkungan secara signifikan.
            </p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300">
                    Get started
                    <svg class="w-4 h-4 ms-2 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="py-3 px-5 sm:ms-4 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-green-600 focus:ring-4 focus:ring-gray-200">
                    Log In
                </a>
            </div>
        </div>
    </section>


</body>
</html>
