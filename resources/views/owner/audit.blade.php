@extends('layouts.owner')

@section('title', 'Master Audit Trail')

@section('content')
<div class="space-y-6" x-data="{ openModal: false, logPayload: '' }">

    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800">Master Audit Trail 🛡️</h1>
        <p class="text-sm text-gray-500 mt-1">Pelacakan real-time terhadap aktivitas sensitif, perubahan data database, dan log masuk pengguna di seluruh sistem JayMart.</p>
    </div>

    <!-- Tabel Audit Log -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Waktu Kejadian</th>
                        <th class="py-4 px-6">Pelaku (User)</th>
                        <th class="py-4 px-6">Modul / Tabel</th>
                        <th class="py-4 px-6">Aksi</th>
                        <th class="py-4 px-6 text-center">Detail Perubahan</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-gray-500 font-mono text-xs">
                            {{ $log->created_at->format('d M Y, H:i:s') }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-gray-800">
                                👤 {{ $log->causer->name ?? 'Sistem Pusat' }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $log->causer->email ?? 'system_auto@jaymart.com' }}
                            </div>
                        </td>
                        <td class="py-4 px-6 font-medium text-gray-700">
                            📂 {{ $log->log_name ?? class_basename($log->subject_type) }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase 
                                @if($log->description == 'created') bg-emerald-50 text-emerald-700
                                @elseif($log->description == 'updated') bg-blue-50 text-blue-700
                                @elseif($log->description == 'deleted') bg-rose-50 text-rose-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $log->description }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($log->properties && count($log->properties) > 0)
                            <button @click="openModal = true; logPayload =  `{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}`"
                                class="text-indigo-600 hover:text-indigo-900 font-medium text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition cursor-pointer">
                                Lihat Data JSON
                            </button>
                            @else
                            <span class="text-gray-400 text-xs italic">Tidak ada perubahan properti</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400 text-sm">
                            🔒 Belum ada rekaman aktivitas log keamanan sensitif yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Log JSON -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-cloak>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800">Detail Perubahan Enkripsi Database 📋</h3>
                <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-xl cursor-pointer">&times;</button>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Payload Perubahan (Attributes vs Old Data)</label>
                <pre class="w-full p-4 border rounded-xl text-xs bg-gray-900 text-emerald-400 font-mono overflow-x-auto max-h-60" x-text="logPayload"></pre>
            </div>

            <div class="flex justify-end items-center pt-3 border-t">
                <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-semibold text-gray-600 cursor-pointer transition">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection