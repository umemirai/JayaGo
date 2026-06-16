@extends('layouts.gudang')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Notifikasi Stok Menipis</h2>
    <p class="text-sm text-gray-500">Daftar produk yang stoknya di bawah atau sama dengan stok minimum</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Produk</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Kode</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Stok Saat Ini</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Stok Minimum</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($lowStocks as $index => $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $lowStocks->firstItem() + $index }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->code ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="font-semibold text-red-600">{{ $product->stock }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $product->stock_minimum }}</td>
                    <td class="px-4 py-3">
                        @if ($product->stock == 0)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Habis
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                Menipis
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        ✅ Semua stok produk dalam kondisi aman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($lowStocks->hasPages())
    <div class="px-4 py-3 border-t border-gray-200">
        {{ $lowStocks->links() }}
    </div>
    @endif
</div>
@endsection