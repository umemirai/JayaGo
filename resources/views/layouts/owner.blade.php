<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owner Dashboard') - JayaGo</title>
    {{-- Ini script v4 yang bikin tampilannya modern --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans">

    {{-- Pakai warna Indigo mewah khusus Owner --}}
    <nav class="bg-indigo-900 text-white px-6 py-3 flex justify-between items-center shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center space-x-2">
            <span class="font-semibold text-lg tracking-wide">JayGo</span>
            <span class="bg-indigo-700 text-xs text-indigo-100 px-3 py-0.5 rounded-full font-medium">Owner</span>
        </div>

        {{-- MENU NAVIGASI EKSKLUSIF PAK JAYUSMAN --}}
        <div class="hidden md:flex space-x-6 text-sm font-medium">
            <a href="{{ route('owner.dashboard') }}" class="pb-1 transition {{ request()->routeIs('owner.dashboard') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                Global Dashboard
            </a>
            <a href="{{ route('owner.branches') }}" class="pb-1 transition {{ request()->routeIs('owner.branches') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                Manajemen Cabang
            </a>
            <a href="{{ route('owner.users') }}" class="pb-1 transition {{ request()->routeIs('owner.users') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                Manajemen User
            </a>
            <a href="{{ route('owner.reports') }}" class="pb-1 transition {{ request()->routeIs('owner.reports') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                Laporan Konsolidasi
            </a>
            <a href="{{ route('owner.audit') }}" class="pb-1 transition {{ request()->routeIs('owner.audit') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                Log Audit
            </a>
        </div>

        <div class="flex items-center space-x-6 text-sm font-medium">
            <span class="text-indigo-200">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 inline">
                @csrf
                <button type="submit" class="text-white hover:text-indigo-200 bg-transparent border-none p-0 cursor-pointer font-medium transition duration-150">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container mx-auto px-6 pt-24 pb-8">
        @yield('content')
    </div>

    @livewireScripts
</body>

</html>