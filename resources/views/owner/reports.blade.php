@extends('layouts.owner')

@section('title', 'Laporan Konsolidasi')

@section('content')
<div class="space-y-6">

    <!-- Header & Form Filter -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Konsolidasi Transaksi 📈</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau arus kas masuk dan akumulasi performa penjualan dari seluruh cabang JayMart.</p>
        </div>

        <!-- FORM FILTER TANGGAL (QUERY SCOPE) -->
        <form action="{{ route('owner.reports') }}" method="GET" class="flex flex-wrap items-center gap-2 m-0 p-0">
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5">
                <input type="date" name="start_date" value="{{ $startDate }}" class="bg-transparent border-0 text-sm p-0 focus:ring-0 text-gray-700 outline-none">
                <span class="text-gray-400 text-xs font-medium">s/d</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="bg-transparent border-0 text-sm p-0 focus:ring-0 text-gray-700 outline-none">
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition cursor-pointer">
                Terapkan Filter
            </button>
            @if($startDate || $endDate)
            <a href="{{ route('owner.reports') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm font-medium transition text-center">Reset</a>
            @endif
        </form>
    </div>

    <!-- Ringkasan Pendapatan -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-md p-6 text-white flex justify-between items-center">
        <div>
            <p class="text-indigo-100 text-xs font-semibold uppercase tracking-wider">Total Omzet Konsolidasi (Periode Terpilih)</p>
            <h3 class="text-3xl font-black mt-1">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
        </div>
        <div class="text-4xl opacity-20 font-bold">💵</div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Waktu Transaksi</th>
                        <th class="py-4 px-6">No. Invoice</th>
                        <th class="py-4 px-6">Kasir / User</th>
                        <th class="py-4 px-6">Asal Cabang</th>
                        <th class="py-4 px-6">Total Belanja</th>
                        <th class="py-4 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-gray-500">
                            {{ $trx->transaction_date ? $trx->transaction_date->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-gray-700">
                            {{ $trx->invoice_number }}
                        </td>
                        <td class="py-4 px-6 text-gray-700">
                            👤 {{ $trx->cashier->name ?? 'Sistem' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-md font-semibold">
                                🏢 {{ $trx->branch->name ?? 'Pusat / Utama' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-gray-800">
                            Rp {{ number_format($trx->total, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="bg-emerald-50 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">
                                {{ $trx->status ?? 'SUCCESS' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 text-sm">
                            🚫 Tidak ditemukan data transaksi pada periode tanggal tersebut.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection