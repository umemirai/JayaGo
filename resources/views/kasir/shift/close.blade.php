@extends('layouts.kasir')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-lg">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tutup Shift</h1>
            <p class="text-gray-500 text-sm mt-1">{{ now()->format('l, d F Y') }}</p>
        </div>

        {{-- Ringkasan Penjualan --}}
        <div class="bg-blue-50 rounded-xl p-4 mb-6 space-y-2 text-sm">
            <h2 class="font-semibold text-blue-800 mb-3">Ringkasan Shift</h2>
            <div class="flex justify-between">
                <span class="text-gray-600">Jam Mulai</span>
                <span class="font-medium">{{ $shift->shift_start }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Uang Awal Kas</span>
                <span class="font-medium">Rp {{ number_format($shift->opening_cash, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-t pt-2 mt-2">
                <span class="text-gray-600">Total Transaksi</span>
                <span class="font-medium">{{ $salesData->total_transactions }} transaksi</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Penjualan</span>
                <span class="font-bold text-blue-700">Rp {{ number_format($salesData->total_sales ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-t pt-2 mt-2">
                <span class="text-gray-600">Ekspektasi Kas</span>
                <span class="font-bold">Rp {{ number_format($shift->opening_cash + ($salesData->total_sales ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('kasir.shift.close.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Uang Kas Akhir (Hitung Fisik)</label>
                <input
                    type="number"
                    name="closing_cash"
                    min="0"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-lg font-bold focus:ring-2 focus:ring-blue-500"
                    placeholder="0"
                    required
                />
                @error('closing_cash')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <textarea name="notes" rows="3"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                    placeholder="Catatan shift..."></textarea>
            </div>

            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition">
                Tutup Shift & Simpan Laporan
            </button>
        </form>

        <a href="{{ route('kasir.pos') }}"
            class="block text-center mt-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 rounded-xl text-sm transition">
            Kembali ke POS
        </a>
    </div>
</div>
@endsection