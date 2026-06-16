<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajer — Jaygo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50">

    @auth
    <nav class="bg-green-700 text-white px-4 py-2 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <span class="font-bold text-lg">Jaygo</span>
            <span class="bg-green-600 text-xs px-2 py-0.5 rounded-full">Kysen Manajer</span>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <span>{{ auth()->user()->name }}</span>
            <span class="text-green-300">|</span>
            <span class="text-green-200">{{ auth()->user()->branch->name ?? '-' }}</span>
            <a href="{{ route('manajer.dashboard') }}" class="hover:bg-green-600 px-3 py-1 rounded-lg text-xs transition">Dashboard</a>
            <a href="{{ route('manajer.laporan.transaksi') }}" class="hover:bg-green-600 px-3 py-1 rounded-lg text-xs transition">Lap. Transaksi</a>
            <a href="{{ route('manajer.laporan.stok') }}" class="hover:bg-green-600 px-3 py-1 rounded-lg text-xs transition">Lap. Stok</a>
            <a href="{{ route('manajer.sdm.index') }}" class="hover:bg-green-600 px-3 py-1 rounded-lg text-xs transition">SDM</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-green-200 hover:text-white text-xs">Logout</button>
            </form>
        </div>
    </nav>
    @endauth

    <main class="p-6">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>