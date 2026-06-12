<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang — JayMart</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-amber-600 text-white px-6 py-3 flex items-center justify-between shadow">
        <div class="flex items-center gap-4">
            <span class="font-bold text-lg">JayMart Gudang</span>
            <div class="flex gap-2 text-sm">
                <a href="{{ route('gudang.dashboard') }}" class="hover:bg-amber-500 px-3 py-1 rounded-lg transition">Dashboard</a>
                <a href="{{ route('gudang.stock.in') }}" class="hover:bg-amber-500 px-3 py-1 rounded-lg transition">Stok Masuk</a>
                <a href="{{ route('gudang.mutation') }}" class="hover:bg-amber-500 px-3 py-1 rounded-lg transition">Mutasi</a>
                <a href="{{ route('gudang.opname') }}" class="hover:bg-amber-500 px-3 py-1 rounded-lg transition">Stock Opname</a>
                <a href="{{ route('gudang.notif') }}" class="hover:bg-amber-500 px-3 py-1 rounded-lg transition relative">
                    Notifikasi
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                    @endif
                </a>
            </div>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-amber-200 hover:text-white">Logout</button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
        @endif

        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>