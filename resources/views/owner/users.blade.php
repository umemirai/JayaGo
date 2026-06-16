@extends('layouts.owner')

@section('title', 'Manajemen User & Penempatan')

@section('content')
<div class="space-y-6" x-data="{ openModal: false, currentUserId: '', currentUserName: '', currentBranchId: '' }">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen User & Penempatan 👔</h1>
        <p class="text-sm text-gray-500 mt-1">Atur penempatan kerja dan penugasan lokasi cabang bagi para Manajer JayMart.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Nama Pengguna</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Role</th>
                        <th class="py-4 px-6">Penempatan Cabang</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 font-semibold text-gray-800">{{ $user->name }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $user->email }}</td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase {{ $user->hasRole('owner') ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ $user->roles->pluck('name')->implode(', ') ?: 'No Role' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($user->branch)
                            <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-md font-semibold">
                                🏢 {{ $user->branch->name }} ({{ $user->branch->address }})
                            </span>
                            @else
                            <span class="text-gray-400 text-xs italic">Belum Ditempatkan</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if(!$user->hasRole('owner'))
                            <button @click="openModal = true; currentUserId='{{ $user->id }}'; currentUserName='{{ $user->name }}'; currentBranchId='{{ $user->branch_id ?? '' }}'" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition cursor-pointer">
                                Atur Cabang
                            </button>
                            @else
                            <span class="text-gray-400 text-xs italic">Akses Penuh Pusat</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-cloak>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800">Penempatan Cabang</h3>
                <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-xl cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('owner.users.updateBranch') }}" method="POST" class="space-y-4 m-0 p-0">
                @csrf
                <input type="hidden" name="user_id" x-model="currentUserId">

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Pegawai</label>
                    <input type="text" x-model="currentUserName" disabled class="w-full px-3 py-2 border rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pilih Lokasi Tugas Cabang</label>
                    <select name="branch_id" x-model="currentBranchId" class="w-full px-3 py-2 border rounded-xl text-sm focus:outline-indigo-600 bg-white">
                        <option value="">-- Bebastugaskan / Tidak Ditempatkan --</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">🏢 {{ $branch->name }} ({{ $branch->address }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end items-center gap-2 pt-3 border-t">
                    <button type="button" @click="openModal = false" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-100 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 cursor-pointer shadow-sm">
                        Simpan Penempatan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection