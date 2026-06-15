<?php

use Livewire\Component;

new class extends Component
{
    // State untuk kontrol Modal
    public $isOpen = false;
    public $selectedVoidId = null;
    public $pinInput = '';
    public $errorMessage = '';
    public $successMessage = '';

    // Simulasi data dummy pengajuan void dari kasir yang butuh persetujuan
    // (Nanti di dunia nyata, ini diambil dari tabel void_requests di database)
    public $voidRequests = [
        [
            'id' => 1,
            'kasir' => 'Budi Kasir',
            'produk' => 'Rokok Marlboro Merah',
            'jumlah' => 2,
            'total_harga' => 'Rp 80.000',
            'alasan' => 'Pembeli salah ambil varian'
        ],
        [
            'id' => 2,
            'kasir' => 'Siti Kasir Realtime',
            'produk' => 'Minyak Goreng Bimoli 2L',
            'jumlah' => 1,
            'total_harga' => 'Rp 38.500',
            'alasan' => 'Uang pembeli kurang'
        ]
    ];

    // Fungsi untuk membuka modal dan memilih request void yang mau diproses
    public function openVoidModal($id)
    {
        $this->selectedVoidId = $id;
        $this->pinInput = '';
        $this->errorMessage = '';
        $this->isOpen = true;
    }

    // Fungsi untuk menutup modal
    public function closeModal()
    {
        $this->isOpen = false;
    }

    // Proses validasi PIN dan eksekusi persetujuan Void
    public function prosesOtorisasi()
    {
        // PIN Hardcode untuk simulasi (nanti bisa dicocokkan dengan column 'pin' di tabel users)
        $pinBenar = '123456';

        if ($this->pinInput === $pinBenar) {
            // Jika PIN benar, hapus request tersebut dari list (simulasi sukses hapus/approve)
            $this->voidRequests = array_filter($this->voidRequests, function ($request) {
                return $request['id'] !== $this->selectedVoidId;
            });

            $this->successMessage = 'Otorisasi berhasil! Item telah dihapus dari keranjang kasir.';
            $this->isOpen = false;

            // Hilangkan pesan sukses setelah 3 detik
            $this->dispatch('clear-message');
        } else {
            $this->errorMessage = 'PIN Supervisor salah! Akses ditolak.';
        }
    }
};
?>

<div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100 mt-6">
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Otorisasi Kunci / Void Item</h2>
        <p class="text-xs text-gray-500">Daftar permintaan penghapusan item salah input dari mesin kasir</p>
    </div>

    @if($successMessage)
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm font-medium">
        {{ $successMessage }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kerugian Nilai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($voidRequests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $req['kasir'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-red-600 font-medium">{{ $req['produk'] }}</span>
                        <span class="text-xs text-gray-400">({{ $req['jumlah'] }}x)</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $req['total_harga'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 italic">"{{ $req['alasan'] }}"</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <button wire:click="openVoidModal({{ $req['id'] }})" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded font-medium transition shadow-sm">
                            Tinjau & Setujui
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aman! Tidak ada permintaan otorisasi void saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl transform transition-all animate-fade-in">

            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900">Validasi Keamanan Void</h3>
                <p class="text-xs text-gray-500 mt-1">Masukkan PIN Supervisor Anda untuk memberikan otorisasi penghapusan barang ini.</p>
            </div>

            @if($errorMessage)
            <div class="mb-3 p-2 bg-red-100 text-red-700 rounded text-xs font-medium">
                {{ $errorMessage }}
            </div>
            @endif

            <form wire:submit.prevent="prosesOtorisasi" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">PIN Supervisor (Kunci Otorisasi)</label>
                    <input type="password" wire:model="pinInput" placeholder="******" maxlength="6" class="w-full px-3 py-2 border rounded-md text-center text-lg font-bold tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" autocomplete="off" required>
                    <p class="text-[10px] text-gray-400 mt-1 text-center">*Petunjuk Demo: masukkan angka <span class="font-bold">123456</span></p>
                </div>

                <div class="flex justify-end space-x-2 pt-2 border-t border-gray-100">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition shadow-sm">
                        Konfirmasi & Lepas Kunci
                    </button>
                </div>
            </form>

        </div>
    </div>
    @endif
</div>