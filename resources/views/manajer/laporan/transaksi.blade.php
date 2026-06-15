@extends('layouts.manajer')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Laporan Transaksi — {{ Auth::user()->branch->name }}</h2>
        <a href="{{ route('manajer.laporan.transaksi.export') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            ⬇ Export Excel
        </a>
    </div>
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">No. Invoice</th>
                    <th class="px-4 py-3 text-left">Kasir</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-left">Metode</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transaksi as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $t->invoice_number }}</td>
                    <td class="px-4 py-3">{{ $t->user->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 capitalize">{{ $t->payment_method }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">{{ $t->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $transaksi->links() }}</div>
    </div>
</div>
@endsection