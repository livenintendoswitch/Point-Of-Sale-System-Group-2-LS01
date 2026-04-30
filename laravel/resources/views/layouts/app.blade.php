<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - @yield('title', 'Aplikasi Kasir')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Menambahkan Font Inter agar lebih modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="h-full">
    <div class="min-h-full">
        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Sisi Kiri: Logo & Menu -->
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <div class="bg-indigo-600 p-2 rounded-lg shadow-sm">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <span class="ml-3 font-bold text-xl tracking-tight text-gray-900">POS<span class="text-indigo-600">App</span></span>
                        </div>

                        <div class="hidden md:ml-8 md:flex md:space-x-4 items-center">
                            @auth
                                <!-- Link Dashboard selalu ada -->
                                <a href="{{ route('dashboard') }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                                    Dashboard
                                </a>

                                @if(auth()->user()->role == 'kasir')
                                    <a href="{{ route('transaksi') }}" 
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('transaksi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                                        Transaksi
                                    </a>
                                @else
                                    <!-- Menu Admin -->
                                    <a href="{{ route('produk.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md">Produk</a>
                                    <a href="{{ route('member.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md">Member</a>
                                    <a href="{{ route('user.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md">User</a>
                                    <a href="{{ route('laporan') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md text-orange-600">Laporan</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Sisi Kanan: Profil & Logout -->
                    <div class="flex items-center">
                        @auth
                        <div class="flex items-center space-x-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                            <div class="h-8 w-px bg-gray-200 mx-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Section -->
        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border-l-4 border-green-400 animate-pulse">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4 border-l-4 border-red-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Yield Content -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>