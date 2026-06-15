<?php

use Livewire\Component;
use App\Models\User;

new class extends Component
{
    // Fungsi ini otomatis jalan setiap kali komponen di-refresh (di-poll)
    public function with(): array
    {
        return [
            // Kita ambil semua user yang rolenya 'kasir' di database kamu
            'kasirAktif' => User::role('kasir')->get()
        ];
    }
};
?>

<div wire:poll.2s class="p-6 bg-white rounded-lg shadow-sm border border-gray-100 mt-6">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Live Monitoring Transaksi Kasir</h2>
            <p class="text-xs text-gray-500">Memantau aktivitas pegawai secara real-time</p>
        </div>
        <div class="flex items-center space-x-2 bg-green-50 px-3 py-1 rounded-full">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <span class="text-xs font-medium text-green-700">Koneksi Livewire Aktif</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email Sistem</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Perangkat</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($kasirAktif as $kasir)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-blue-600">{{ $kasir->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $kasir->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse">
                            Terhubung ke POS
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada kasir terdaftar di database.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>