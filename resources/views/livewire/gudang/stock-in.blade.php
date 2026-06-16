@extends('layouts.gudang')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Input Stok Masuk</h1>
    <p class="text-gray-500 text-sm mb-6">Tambahkan barang yang baru diterima dari supplier.</p>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('gudang.stock.in.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                <select name="product_id" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="">— Pilih Produk —</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->barcode }}) — Stok saat ini: {{ $product->stock }} {{ $product->unit ?? '' }}
                    </option>
                    @endforeach
                </select>
                @error('product_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Masuk</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity') }}" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500"
                        placeholder="0"/>
                    @error('quantity')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Referensi / PO (opsional)</label>
                    <input type="text" name="reference" value="{{ old('reference') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500"
                        placeholder="PO-2026-001"/>
                    @error('reference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier (opsional)</label>
                <input type="text" name="supplier" value="{{ old('supplier') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500"
                    placeholder="Nama supplier"/>
                @error('supplier')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <textarea name="notes" rows="3"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500"
                    placeholder="Misal: barang dari supplier Indofood, kondisi baik...">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 rounded-xl transition">
                Simpan Stok Masuk
            </button>
        </form>
    </div>
</div>
@endsection