@extends('layouts.manajer')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Laporan Stok — {{ Auth::user()->branch->name }}</h2>
        <a href="{{ route('manajer.laporan.stok.export') }}"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            ⬇ Export PDF
        </a>
    </div>
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Barcode</th>
                    <th class="px-4 py-3 text-left">Nama Produk</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-right">Harga</th>
                    <th class="px-4 py-3 text-right">Stok</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($produk as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $p->barcode }}</td>
                    <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $p->category }}</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right font-bold {{ $p->stock <= $p->stock_minimum ? 'text-red-600' : '' }}">{{ $p->stock }}</td>
                    <td class="px-4 py-3">
                        @if($p->stock <= $p->stock_minimum)
                            <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">Menipis</span>
                        @else
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection