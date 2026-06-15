<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Supervisor Dashboard') - JayaGo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans">

    @php
    $jumlahVoidPending = 1;
    $jumlahOpnamePending = 1;
    @endphp

    <nav class="bg-blue-600 text-white px-6 py-3 flex justify-between items-center shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center space-x-2">
            <span class="font-semibold text-lg tracking-wide">JayMart</span>
            <span class="bg-blue-500 text-xs text-blue-100 px-3 py-0.5 rounded-full font-medium">Supervisor</span>
        </div>

        <div class="hidden md:flex space-x-6 text-sm font-medium">
            <a href="{{ route('supervisor.monitoring') }}" class="pb-1 transition {{ request()->routeIs('supervisor.monitoring') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                Live Monitoring
            </a>

            <a href="{{ route('supervisor.void') }}" class="pb-1 transition flex items-center space-x-1.5 {{ request()->routeIs('supervisor.void') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                <span>Otorisasi Void</span>

                @if($jumlahVoidPending > 0)
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
                @endif
            </a>

            <a href="{{ route('supervisor.opname') }}" class="pb-1 transition flex items-center space-x-1.5 {{ request()->routeIs('supervisor.opname') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                <span>Validasi Opname</span>

                @if($jumlahOpnamePending > 0)
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
                @endif
            </a>

            <a href="{{ route('supervisor.audit') }}" class="pb-1 transition {{ request()->routeIs('supervisor.audit') ? 'border-b-2 border-white font-bold' : 'hover:text-gray-200 opacity-80' }}">
                Log Audit
            </a>
        </div>

        <div class="flex items-center space-x-6 text-sm font-medium">
            <span class="text-blue-100">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 inline">
                @csrf
                <button type="submit" class="text-white hover:text-blue-200 bg-transparent border-none p-0 cursor-pointer font-medium transition duration-150">
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