<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">POS App</div>
                    <div class="hidden md:ml-6 md:flex space-x-8">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2">Dashboard</a>
                        @if(auth()->user()->role == 'kasir')
                        <a href="{{ route('transaksi') }}" class="px-3 py-2">Transaksi</a>
                        @else
                        <a href="{{ route('produk.index') }}" class="px-3 py-2">Produk</a>
                        <a href="{{ route('member.index') }}" class="px-3 py-2">Member</a>
                        <a href="{{ route('user.index') }}" class="px-3 py-2">User</a>
                        <a href="{{ route('laporan') }}" class="px-3 py-2">Laporan</a>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</body>

</html>