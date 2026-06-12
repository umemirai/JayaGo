<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir — JayMart</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50">

    @auth
    <nav class="bg-blue-700 text-white px-4 py-2 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <span class="font-bold text-lg">JayMart POS</span>
            <span class="bg-blue-600 text-xs px-2 py-0.5 rounded-full">Kasir</span>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <span>{{ auth()->user()->name }}</span>
            <a href="{{ route('kasir.shift.close') }}"
                class="bg-yellow-500 hover:bg-yellow-400 px-3 py-1 rounded-lg font-medium text-xs transition">
                Tutup Shift
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-blue-200 hover:text-white text-xs">Logout</button>
            </form>
        </div>
    </nav>
    @endauth

    <main class="h-[calc(100vh-42px)]">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>