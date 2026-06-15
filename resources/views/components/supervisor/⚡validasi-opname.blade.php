<?php

use Livewire\Component;

new class extends Component
{
    public $successMessage = '';

    // Simulasi data dummy laporan opname dari tim gudang yang masuk ke meja Supervisor
    // (Di duni nyata, data ini diambil langsung dari tabel stock_opnames)
    public $opnameReports = [
        [
            'id' => 101,
            'petugas' => 'Ani Gudang',
            'produk' => 'Susu Ultra Milk Cokelat 1L',
            'stok_sistem' => 50,
            'stok_fisik' => 48,
            'selisih' => -2,
            'keterangan' => 'Bocor/rusak di rak gudang',
            'status' => 'Pending'
        ],
        [
            'id' => 102,
            'petugas' => 'Ani Gudang',
            'produk' => 'Beras Pandan Wangi 5kg',
            'stok_sistem' => 20,
            'stok_fisik' => 21,
            'selisih' => 1,
            'keterangan' => 'Kelebihan kiriman dari supplier',
            'status' => 'Pending'
        ]
    ];

    // Fungsi Eloquent Update (Simulasi) untuk Menyetujui Laporan
    public function approveOpname($id)
    {
        foreach ($this->opnameReports as &$report) {
            if ($report['id'] === $id) {
                // 1. Mengubah status laporan menjadi Approved
                $report['status'] = 'Approved';

                // 2. Simulasi Eloquent Update ke database asli nantinya:
                // $opname = StockOpname::find($id);
                // $opname->update(['status' => 'Approved']);
                // $product = Product::find($opname->product_id);
                // $product->update(['stok' => $opname->stok_fisik']);

                $this->successMessage = "Laporan Opname #{$id} Berhasil Disetujui! Stok produk di sistem telah disesuaikan.";
                break;
            }
        }
    }

    // Fungsi Eloquent Update (Simulasi) untuk Menolak Laporan
    public function rejectOpname($id)
    {
        foreach ($this->opnameReports as &$report) {
            if ($report['id'] === $id) {
                $report['status'] = 'Rejected';
                $this->successMessage = "Laporan Opname #{$id} ditolak. Stok sistem tidak berubah.";
                break;
            }
        }
    }
};
?>

<div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100 mt-6">
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Validasi Stock Opname</h2>
        <p class="text-xs text-gray-500">Persetujuan laporan selisih stok fisik toko yang diajukan oleh tim gudang</p>
    </div>

    @if($successMessage)
    <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded text-sm font-medium animate-fade-in">
        {{ $successMessage }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Fisik</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Selisih</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @foreach($opnameReports as $rep)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">{{ $rep['petugas'] }}</td>
                    <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-800">{{ $rep['produk'] }}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500">{{ $rep['stok_sistem'] }}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-900 font-semibold">{{ $rep['stok_fisik'] }}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <span class="font-bold {{ $rep['selisih'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $rep['selisih'] > 0 ? '+'.$rep['selisih'] : $rep['selisih'] }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $rep['keterangan'] }}">
                        {{ $rep['keterangan'] }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        @if($rep['status'] === 'Pending')
                        <span class="px-2.5 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                        @elseif($rep['status'] === 'Approved')
                        <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Approved</span>
                        @else
                        <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Rejected</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center space-x-1">
                        @if($rep['status'] === 'Pending')
                        <button wire:click="approveOpname({{ $rep['id'] }})" class="bg-green-600 hover:bg-green-700 text-white text-[11px] px-2.5 py-1 rounded font-medium transition shadow-sm">
                            Setuju
                        </button>
                        <button wire:click="rejectOpname({{ $rep['id'] }})" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-[11px] px-2.5 py-1 rounded font-medium transition">
                            Tolak
                        </button>
                        @else
                        <span class="text-xs text-gray-400 italic">Selesai diperiksa</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>