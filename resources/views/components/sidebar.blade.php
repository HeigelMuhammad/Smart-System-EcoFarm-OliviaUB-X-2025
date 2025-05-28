<aside 
    id="logo-sidebar" 
    class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-300 transform -translate-x-full sm:translate-x-0 transition-transform duration-300 ease-in-out" 
    aria-label="Sidebar"
>
    <div class="h-full px-3 py-4 overflow-y-auto">
        <a href="#" class="flex items-center ps-2.5 mb-5">
            <img src="{{ asset('img/clean.png') }}" class="h-6 me-3 sm:h-7" alt="Eco Farm Logo" />
            <span class="self-center text-3xl font-semibold whitespace-nowrap text-black">Eco Farm</span>
        </a>

        @auth
            <div class="text-m text-gray-700 font-semibold px-2 py-2 mb-4">
                Halo, {{ Auth::user()->name }}
            </div>
        @endauth

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('monitoring') }}"
                    class="flex items-center p-2 rounded-lg group
                    {{ request()->is('monitoring') ? 'bg-green-200 text-green-700' : 'text-gray-900 hover:bg-green-200 hover:text-green-700' }}">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-green-700" fill="currentColor" viewBox="0 0 22 21">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066Z"/>
                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('rekomendasi') }}"
                    class="flex items-center p-2 rounded-lg group
                    {{ request()->is('rekomendasi') ? 'bg-green-200 text-green-700' : 'text-gray-900 hover:bg-green-200 hover:text-green-700' }}">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-green-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Recommendation</span>
                </a>
            </li>
            <li class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-red-200 hover:text-red-700 group">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</aside>
