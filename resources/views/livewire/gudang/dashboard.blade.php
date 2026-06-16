@extends('layouts.gudang')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Gudang</h1>
            <p class="text-gray-500 text-sm mt-1">{{ now()->format('l, d F Y') }}</p>
        </div>
        <a href="{{ route('gudang.stock.in') }}"
            class="bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-xl text-sm transition">
            + Input Stok Masuk
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Peringatan Stok Rendah --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-red-500">⚠</span> Peringatan Stok Rendah
                <span class="ml-auto text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ $lowStockProducts->count() }}</span>
            </h2>

            @if($lowStockProducts->isEmpty())
            <p class="text-gray-400 text-sm py-6 text-center">Semua stok aman 👍</p>
            @else
            <div class="space-y-2">
                @foreach($lowStockProducts as $product)
                <div class="flex items-center justify-between bg-red-50 rounded-lg px-3 py-2">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $product->barcode }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600 text-sm">{{ $product->stock }} {{ $product->unit ?? '' }}</p>
                        <p class="text-xs text-gray-400">min: {{ $product->stock_minimum }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Mutasi Terbaru --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Mutasi Terbaru</h2>

            @if($recentMutations->isEmpty())
            <p class="text-gray-400 text-sm py-6 text-center">Belum ada mutasi.</p>
            @else
            <div class="space-y-2 max-h-80 overflow-y-auto">
                @foreach($recentMutations as $mutation)
                <div class="flex items-center justify-between border-b last:border-0 pb-2">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $mutation->product->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">
                            {{ ucfirst($mutation->type) }} · {{ $mutation->user->name ?? '-' }} ·
                            {{ $mutation->created_at->format('d/m H:i') }}
                        </p>
                    </div>
                    <span class="font-bold text-sm {{ $mutation->quantity_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $mutation->quantity_change >= 0 ? '+' : '' }}{{ $mutation->quantity_change }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</div>
@endsection