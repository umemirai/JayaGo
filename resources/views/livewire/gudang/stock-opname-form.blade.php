<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Stock Opname</h2>
        <p class="text-sm text-gray-500">Periksa dan perbarui stok barang di gudang</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow p-6">
        
        {{-- Notes / Keterangan --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
            <textarea wire:model="notes" rows="2"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Catatan stock opname..."></textarea>
        </div>

        {{-- Tabel Items --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-600">No</th>
                        <th class="px-4 py-2 text-left text-gray-600">Nama Barang</th>
                        <th class="px-4 py-2 text-left text-gray-600">Stok Sistem</th>
                        <th class="px-4 py-2 text-left text-gray-600">Stok Fisik</th>
                        <th class="px-4 py-2 text-left text-gray-600">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($items as $index => $item)
                    <tr>
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $item['nama'] }}</td>
                        <td class="px-4 py-2">{{ $item['stok_sistem'] }}</td>
                        <td class="px-4 py-2">
                            <input type="number" wire:model="items.{{ $index }}.stok_fisik"
                                class="w-24 border border-gray-300 rounded px-2 py-1 text-sm"
                                min="0">
                        </td>
                        <td class="px-4 py-2">
                            {{ ($item['stok_fisik'] ?? 0) - $item['stok_sistem'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-400">
                            Tidak ada data barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tombol Simpan --}}
        <div class="mt-4 flex justify-end">
            <button wire:click="save"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm font-medium">
                Simpan Opname
            </button>
        </div>
    </div>
</div>