@extends('layouts.kasir-page')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Buka Shift</h1>
            <p class="text-gray-500 text-sm mt-1">{{ now()->format('l, d F Y') }}</p>
        </div>

        <form method="POST" action="{{ route('kasir.shift.open.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Uang Awal Kas (Rp)</label>
                <input
                    type="number"
                    name="opening_cash"
                    min="0"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-lg font-bold focus:ring-2 focus:ring-blue-500"
                    placeholder="0"
                    required
                />
                @error('opening_cash')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                Mulai Shift & Buka Kasir
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 rounded-xl text-sm transition">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection