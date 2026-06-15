@extends('layouts.manajer')

@section('content')
<div class="max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        Dashboard Cabang — {{ Auth::user()->branch->name ?? '-' }}
    </h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-600 text-white rounded-xl p-4 shadow">
            <p class="text-sm opacity-80">Transaksi Bulan Ini</p>
            <p class="text-3xl font-bold mt-1">{{ $totalTransaksi }}</p>
        </div>
        <div class="bg-green-600 text-white rounded-xl p-4 shadow">
            <p class="text-sm opacity-80">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-yellow-500 text-white rounded-xl p-4 shadow">
            <p class="text-sm opacity-80">Stok Menipis</p>
            <p class="text-3xl font-bold mt-1">{{ $stokMenipis }}</p>
        </div>
        <div class="bg-cyan-600 text-white rounded-xl p-4 shadow">
            <p class="text-sm opacity-80">Total Karyawan</p>
            <p class="text-3xl font-bold mt-1">{{ $jumlahKaryawan }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Transaksi 7 Hari Terakhir</h3>
        <canvas id="chartTransaksi" height="100"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('chartTransaksi'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($transaksiHarian->pluck('tanggal')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($transaksiHarian->pluck('pendapatan')) !!},
                backgroundColor: 'rgba(22, 163, 74, 0.6)',
                borderColor: 'rgba(22, 163, 74, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush