@extends('layouts.owner')

@section('title', 'Global Dashboard')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali, Pak Jayusman! 👋</h1>
            <p class="text-sm text-gray-500 mt-1">Berikut adalah laporan performa bisnis dan omzet real-time dari 5 cabang JayMart.</p>
        </div>
        <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-semibold border border-indigo-100 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
            Mode: Monitoring Pusat
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Omzet Global</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 68.500.000</h3>
            <span class="text-xs text-emerald-600 font-medium mt-2 block">↑ 12% dari bulan lalu</span>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Cabang Aktif</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">5 Lokasi</h3>
            <span class="text-xs text-gray-500 font-medium mt-2 block">Jakarta, Bandung, Sby, Mdn, Mks</span>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Manajer</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">5 Orang</h3>
            <span class="text-xs text-emerald-600 font-medium mt-2 block">✓ Semua cabang terisi</span>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Aktivitas Sistem (Hari Ini)</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">142 Log</h3>
            <span class="text-xs text-indigo-600 font-medium mt-2 block">→ Terpantau di Audit Trail</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">Grafik Perbandingan Omzet Antar Cabang</h2>
                <span class="text-xs text-gray-400">Mata Uang: IDR (Rupiah)</span>
            </div>
            <div class="relative h-[320px] w-full">
                <canvas id="omzetChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4">Performa Cabang tertinggi</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <div>
                            <p class="font-bold text-emerald-900 text-sm">1. Cabang Surabaya</p>
                            <p class="text-xs text-emerald-700">Manajer: Budianto</p>
                        </div>
                        <span class="font-extrabold text-emerald-800 text-sm">Rp 18.000.000</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">2. Cabang Jakarta</p>
                            <p class="text-xs text-gray-500">Manajer: Ahmad</p>
                        </div>
                        <span class="font-bold text-gray-700 text-sm">Rp 15.000.000</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">3. Cabang Makassar</p>
                            <p class="text-xs text-gray-500">Manajer: Siti</p>
                        </div>
                        <span class="font-bold text-gray-700 text-sm">Rp 14.000.000</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center">Data diperbarui otomatis setiap terjadi transaksi di kasir.</p>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mengambil data dummy dari Controller secara aman lewat JSON Blade
        const chartData = @json($dataOmzet);

        const ctx = document.getElementById('omzetChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Jenis grafik batang
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Total Pendapatan (Rp)',
                    data: chartData.values,
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.7)', // Jakarta (Indigo)
                        'rgba(59, 130, 246, 0.7)', // Bandung (Blue)
                        'rgba(16, 185, 129, 0.7)', // Surabaya (Emerald)
                        'rgba(245, 158, 11, 0.7)', // Medan (Amber)
                        'rgba(139, 92, 246, 0.7)' // Makassar (Purple)
                    ],
                    borderColor: [
                        'rgb(99, 102, 241)',
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(139, 92, 246)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8, // Bikin ujung batangnya membulat modern
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan kotak label atas karena warna warni berbeda
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(243, 244, 246, 1)'
                        },
                        ticks: {
                            callback: function(value) {
                                // Format angka sumbu Y ke bentuk "Juta" agar ringkas
                                return 'Rp ' + (value / 1000000) + ' Jt';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection