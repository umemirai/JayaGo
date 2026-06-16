@extends('layouts.gudang')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Mutasi Barang</h1>
        <p class="text-gray-500 text-sm mt-1">Catat perpindahan barang dari gudang ke rak display atau penyesuaian stok.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Mutasi --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 lg:col-span-1">
            <h2 class="font-semibold text-gray-800 mb-4">Catat Mutasi Baru</h2>

            <form method="POST" action="{{ route('gudang.mutation.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                    <select name="product_id" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="">— Pilih Produk —</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Mutasi</label>
                    <select name="type" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="">— Pilih Jenis —</option>
                        <option value="display">Pindah ke Rak Display</option>
                        <option value="adjustment_in">Penyesuaian Stok (+)</option>
                        <option value="adjustment_out">Penyesuaian Stok (−)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="quantity" min="1" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500"
                        placeholder="0"/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan / Alasan</label>
                    <textarea name="notes" rows="3" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500"
                        placeholder="Misal: restock rak snack depan kasir"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 rounded-xl transition">
                    Simpan Mutasi
                </button>
            </form>
        </div>

        {{-- Riwayat Mutasi --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 lg:col-span-2">
            <h2 class="font-semibold text-gray-800 mb-4">Riwayat Mutasi</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-3 py-2 text-gray-600 font-medium">Tanggal</th>
                            <th class="text-left px-3 py-2 text-gray-600 font-medium">Produk</th>
                            <th class="text-left px-3 py-2 text-gray-600 font-medium">Jenis</th>
                            <th class="text-right px-3 py-2 text-gray-600 font-medium">Perubahan</th>
                            <th class="text-right px-3 py-2 text-gray-600 font-medium">Stok Akhir</th>
                            <th class="text-left px-3 py-2 text-gray-600 font-medium">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($mutations as $mutation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-gray-500">{{ $mutation->mutation_date->format('d/m/Y H:i') }}</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $mutation->product->name }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ match($mutation->type) {
                                        'in' => 'bg-green-100 text-green-700',
                                        'out' => 'bg-red-100 text-red-700',
                                        'display' => 'bg-blue-100 text-blue-700',
                                        'opname' => 'bg-purple-100 text-purple-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    } }}">
                                    {{ ucfirst($mutation->type) }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-right font-semibold {{ $mutation->quantity_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $mutation->quantity_change >= 0 ? '+' : '' }}{{ $mutation->quantity_change }}
                            </td>
                            <td class="px-3 py-2 text-right text-gray-600">{{ $mutation->quantity_after }}</td>
                            <td class="px-3 py-2 text-gray-500">{{ $mutation->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-8">Belum ada riwayat mutasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $mutations->links() }}
            </div>
        </div>

    </div>
</div>
@endsection