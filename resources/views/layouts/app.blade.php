<!DOCTYPE html>
<html lang="en">
<head>
    @stack('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berry Market</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    @stack('scripts')

    <!-- Sidebar -->
    <div class="w-64 h-screen fixed top-0 left-0 bg-white text-blue-800 shadow-md border-r border-gray-200 px-4 py-6">
        <div class="flex items-center gap-3 mb-8">
            <span class="material-symbols-outlined text-4xl text-blue-600">
                storefront
            </span>
            <span class="text-2xl font-bold tracking-wide">BerryMarket</span>
        </div>

        <!-- Navigation -->
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-md transition
                        {{ Request::routeIs('dashboard') ? 'bg-blue-100 text-blue-900 font-semibold' : 'hover:bg-blue-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M13 5v6m-7 2h14a2 2 0 012 2v7H4v-7a2 2 0 012-2z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-md transition
                        {{ Request::routeIs('products.index') ? 'bg-blue-100 text-blue-900 font-semibold' : 'hover:bg-blue-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V7a2 2 0 00-2-2h-4.586a1 1 0 00-.707.293L10 7.586a1 1 0 00-.293.707V13a2 2 0 002 2h6a2 2 0 002-2z" />
                    </svg>
                    <span>Produk</span>
                </a>
            </li>   
            <li>
                <a href="{{ route('purchases.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-md transition
                        {{ Request::routeIs('purchases.index') ? 'bg-blue-100 text-blue-900 font-semibold' : 'hover:bg-blue-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.293 2.293a1 1 0 00-.207.707v.5a1 1 0 001 1h12a1 1 0 001-1v-.5a1 1 0 00-.207-.707L17 13M7 13V6m0 0L5 4m2 2h11" />
                    </svg>
                    <span>Pembelian</span>
                </a>
            </li>
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('user.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-md transition
                        {{ Request::routeIs('user.index') ? 'bg-blue-100 text-blue-900 font-semibold' : 'hover:bg-blue-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A9.953 9.953 0 0112 15c2.21 0 4.24.716 5.879 1.926M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>User</span>
                </a>
            </li>
            @endif
        </ul>
    </div>

    <div class="ml-64 min-h-screen">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    @yield('title', 'Dashboard')
                </h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-800">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>

            <div>
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
