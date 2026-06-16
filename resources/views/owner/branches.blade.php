@extends('layouts.owner')

@section('title', 'Manajemen Cabang')

@section('content')
<div class="space-y-6" x-data="{ openModal: false, editMode: false, currentId: '', currentName: '', currentAddress: '', currentPhone: '' }">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Cabang 🏢</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data informasi operasional untuk lokasi toko utama JayMart.</p>
        </div>
        <button @click="openModal = true; editMode = false; currentId=''; currentName=''; currentAddress=''; currentPhone=''" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2 cursor-pointer">
            <span>+ Tambah Cabang Baru</span>
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Nama Cabang</th>
                        <th class="py-4 px-6">Alamat / Lokasi Kota</th>
                        <th class="py-4 px-6">Nomor Telepon</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($branches as $branch)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 font-medium text-gray-400">#0{{ $branch->id }}</td>
                        <td class="py-4 px-6 font-semibold text-gray-800">{{ $branch->name }}</td>
                        <td class="py-4 px-6">
                            <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-md font-medium">
                                {{ $branch->address }} {{-- Kolom Alamat database digunakan sebagai lokasi --}}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $branch->phone ?? 'Tidak ada kontak' }}</td>
                        <td class="py-4 px-6">
                            <div class="flex justify-center items-center gap-3">
                                <button @click="openModal = true; editMode = true; currentId='{{ $branch->id }}'; currentName='{{ $branch->name }}'; currentAddress='{{ $branch->address }}'; currentPhone='{{ $branch->phone ?? '' }}'" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition cursor-pointer">
                                    Edit
                                </button>
                                <a href="{{ route('owner.branches.delete', $branch->id) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus cabang {{ $branch->name }}?')" class="text-rose-600 hover:text-rose-900 font-medium text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition text-center decoration-none">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                            Belum ada data cabang di database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-cloak>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800" x-text="editMode ? 'Edit Data Cabang' : 'Tambah Cabang Baru'"></h3>
                <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-xl cursor-pointer">&times;</button>
            </div>

            <form :action="editMode ? '{{ route('owner.branches.update') }}' : '{{ route('owner.branches.store') }}'" method="POST" class="space-y-4 m-0 p-0">
                @csrf
                <input type="hidden" name="id" x-model="currentId">

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Cabang</label>
                        <input type="text" name="name" x-model="currentName" required class="w-full px-3 py-2 border rounded-xl text-sm focus:outline-indigo-600" placeholder="Contoh: JayMart Jakarta Pusat">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Alamat / Lokasi Kota</label>
                        <input type="text" name="address" x-model="currentAddress" required class="w-full px-3 py-2 border rounded-xl text-sm focus:outline-indigo-600" placeholder="Contoh: Jakarta Pusat">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nomor Telepon (Opsional)</label>
                        <input type="text" name="phone" x-model="currentPhone" class="w-full px-3 py-2 border rounded-xl text-sm focus:outline-indigo-600" placeholder="Contoh: 021-xxxxxx">
                    </div>
                </div>

                <div class="flex justify-end items-center gap-2 pt-3 border-t">
                    <button type="button" @click="openModal = false" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-100 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 cursor-pointer shadow-sm">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection