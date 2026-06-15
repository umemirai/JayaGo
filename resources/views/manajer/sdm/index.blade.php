@extends('layouts.manajer')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen SDM — {{ Auth::user()->branch->name }}</h2>
        <a href="{{ route('manajer.sdm.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            + Tambah Karyawan
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($karyawan as $k)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $k->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $k->email }}</td>
                    <td class="px-4 py-3">
                        @if($k->role === 'supervisor')
                            <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs">Supervisor</span>
                        @elseif($k->role === 'kasir')
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">Kasir</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs">Pegawai Gudang</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('manajer.sdm.edit', $k) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs mr-1">Edit</a>
                        <form action="{{ route('manajer.sdm.destroy', $k) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin hapus karyawan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada karyawan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection