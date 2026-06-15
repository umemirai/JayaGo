<?php

use Livewire\Component;

new class extends Component
{
    // State untuk menampung input filter dari user
    public $searchPegawai = '';
    public $filterAksi = '';

    // Simulasi data dummy Log Aktivitas Pegawai di Cabang JayMart
    // (Di dunia nyata, data ini diambil dari tabel activity_log hasil package seperti Spatie Activitylog)
    public function getLogsProperty()
    {
        $allLogs = [
            [
                'waktu' => '2026-06-14 10:15:22',
                'pegawai' => 'Budi Kasir',
                'role' => 'Kasir',
                'aksi' => 'Login Sistem',
                'detail' => 'Melakukan login perangkat POS Meja 1'
            ],
            [
                'waktu' => '2026-06-14 11:02:10',
                'pegawai' => 'Ani Gudang',
                'role' => 'Gudang',
                'aksi' => 'Input Opname',
                'detail' => 'Mengajukan selisih -2 Susu Ultra Milk'
            ],
            [
                'waktu' => '2026-06-14 11:45:05',
                'pegawai' => 'Budi Kasir',
                'role' => 'Kasir',
                'aksi' => 'Request Void',
                'detail' => 'Meminta otorisasi hapus Rokok Marlboro'
            ],
            [
                'waktu' => '2026-06-14 13:20:18',
                'pegawai' => 'Ani Gudang',
                'role' => 'Gudang',
                'aksi' => 'Update Stok',
                'detail' => 'Mengubah stok Beras Pandan Wangi (+1)'
            ],
            [
                'waktu' => '2026-06-14 14:00:00',
                'pegawai' => 'Budi Kasir',
                'role' => 'Kasir',
                'aksi' => 'Logout Sistem',
                'detail' => 'Menutup shift dan melakukan logout'
            ],
        ];

        // Logika Livewire Filter (Pencarian Real-time secara dinamis)
        return array_filter($allLogs, function ($log) {
            $matchSearch = empty($this->searchPegawai) ||
                stripos($log['pegawai'], $this->searchPegawai) !== false;

            $matchAksi = empty($this->filterAksi) ||
                $log['aksi'] === $this->filterAksi;

            return $matchSearch && $matchAksi;
        });
    }
};
?>

<div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100 mt-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Log Activity Branch</h2>
        <p class="text-xs text-gray-500">Memantau rekam jejak digital dan riwayat aksi seluruh pegawai di cabang</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-md">
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cari Nama Pegawai</label>
            <input type="text" wire:model.live="searchPegawai" placeholder="Ketik nama (Contoh: Budi, Ani)..." class="w-full px-3 py-1.5 border rounded text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Filter Jenis Aksi</label>
            <select wire:model.live="filterAksi" class="w-full px-3 py-1.5 border rounded text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Semua Aktivitas --</option>
                <option value="Login Sistem">Login Sistem</option>
                <option value="Input Opname">Input Opname</option>
                <option value="Request Void">Request Void</option>
                <option value="Update Stok">Update Stok</option>
                <option value="Logout Sistem">Logout Sistem</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Waktu Log</th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Nama Pegawai</th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Aksi / Tindakan</th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Detail Log</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($this->logs as $log)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-400 font-mono">{{ $log['waktu'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $log['pegawai'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded {{ $log['role'] === 'Kasir' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $log['role'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap font-semibold text-blue-600 text-xs">{{ $log['aksi'] }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $log['detail'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">Tidak ada log aktivitas yang cocok dengan kriteria filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>