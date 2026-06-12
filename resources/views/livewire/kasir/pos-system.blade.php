<div class="flex h-screen bg-gray-100" x-data="{}">

    {{-- Panel Kiri: Pencarian & Keranjang --}}
    <div class="flex-1 flex flex-col p-4 overflow-hidden">
        <h1 class="text-xl font-bold text-gray-800 mb-4">Point of Sale</h1>

        {{-- Search --}}
        <div class="relative mb-4">
            <input
                type="text"
                wire:model.live.debounce.300ms="searchQuery"
                placeholder="Scan barcode atau ketik nama produk..."
                class="w-full pl-10 pr-4 py-3 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 bg-white"
                autofocus
            />
            <svg class="absolute left-3 top-3.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>

            {{-- Dropdown hasil pencarian --}}
            @if($this->searchResults->count() > 0)
            <div class="absolute z-50 top-12 left-0 right-0 bg-white rounded-xl shadow-lg border max-h-72 overflow-y-auto">
                @foreach($this->searchResults as $product)
                <button
                    wire:click="addToCart({{ $product->id }})"
                    class="w-full flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition text-left border-b last:border-0"
                >
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 text-sm">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $product->barcode }} · Stok: {{ $product->stock }}</p>
                    </div>
                    <span class="font-bold text-blue-600 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Tabel Keranjang --}}
        <div class="flex-1 bg-white rounded-xl shadow-sm overflow-y-auto">
            @if(empty($cart))
            <div class="flex flex-col items-center justify-center h-full text-gray-400 py-20">
                <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-sm">Keranjang kosong. Scan atau cari produk.</p>
            </div>
            @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b sticky top-0">
                    <tr>
                        <th class="text-left px-4 py-3 text-gray-600 font-medium">Produk</th>
                        <th class="text-center px-4 py-3 text-gray-600 font-medium w-32">Qty</th>
                        <th class="text-right px-4 py-3 text-gray-600 font-medium">Harga</th>
                        <th class="text-right px-4 py-3 text-gray-600 font-medium">Subtotal</th>
                        <th class="w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($cart as $productId => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-400">{{ $item['barcode'] }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="updateQty({{ $productId }}, {{ $item['qty'] - 1 }})"
                                    class="w-7 h-7 rounded-full bg-gray-100 hover:bg-red-100 flex items-center justify-center font-bold text-gray-600">−</button>
                                <span class="w-8 text-center font-medium">{{ $item['qty'] }}</span>
                                <button wire:click="updateQty({{ $productId }}, {{ $item['qty'] + 1 }})"
                                    class="w-7 h-7 rounded-full bg-gray-100 hover:bg-green-100 flex items-center justify-center font-bold text-gray-600">+</button>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="removeFromCart({{ $productId }})" class="text-red-400 hover:text-red-600">✕</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Panel Kanan: Ringkasan & Bayar --}}
    <div class="w-80 bg-white shadow-lg flex flex-col p-5 gap-4 overflow-y-auto">
        <h2 class="font-bold text-gray-800 text-lg border-b pb-3">Ringkasan</h2>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Diskon</span>
                <input type="number" wire:model.live="discount" min="0"
                    class="w-28 text-right border rounded-lg px-2 py-1 text-sm" placeholder="0"/>
            </div>
            <div class="flex justify-between text-base font-bold text-blue-700 border-t pt-3">
                <span>TOTAL</span>
                <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div>
            <p class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wide">Metode Bayar</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['cash' => 'Tunai', 'debit' => 'Debit', 'qris' => 'QRIS'] as $val => $label)
                <label class="flex items-center justify-center cursor-pointer border rounded-lg px-2 py-2 text-xs font-medium
                    {{ $paymentMethod === $val ? 'border-blue-500 bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <input type="radio" wire:model.live="paymentMethod" value="{{ $val }}" class="sr-only"/>
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Input Bayar --}}
        @if($paymentMethod === 'cash')
        <div>
            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Uang Diterima</label>
            <input type="number" wire:model.live="paid"
                class="w-full mt-1 border rounded-xl px-3 py-2 text-lg font-bold text-center focus:ring-2 focus:ring-blue-500"
                placeholder="0"/>
            @if($this->paid >= $this->total && $this->total > 0)
            <div class="mt-2 bg-green-50 rounded-lg px-3 py-2 flex justify-between text-sm">
                <span class="text-green-600">Kembalian</span>
                <span class="font-bold text-green-700">Rp {{ number_format($this->change, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>
        @endif

        {{-- Tombol --}}
        <div class="mt-auto space-y-2">
            <button
                wire:click="processTransaction"
                wire:loading.attr="disabled"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl text-lg transition disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="processTransaction">Proses Pembayaran</span>
                <span wire:loading wire:target="processTransaction">Memproses...</span>
            </button>

            <button wire:click="clearCart"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 rounded-xl text-sm transition">
                Kosongkan Keranjang
            </button>
        </div>
    </div>

    {{-- Toast Notifikasi --}}
    <div
        x-data="{ notifications: [] }"
        @notify.window="
            let n = { id: Date.now(), type: $event.detail.type, message: $event.detail.message };
            notifications.push(n);
            setTimeout(() => notifications = notifications.filter(i => i.id !== n.id), 3500);
        "
        class="fixed bottom-4 right-4 z-50 space-y-2"
    >
        <template x-for="n in notifications" :key="n.id">
            <div x-show="true" x-transition
                class="px-4 py-3 rounded-xl text-sm font-medium shadow-lg text-white min-w-48"
                :class="{
                    'bg-green-600': n.type === 'success',
                    'bg-red-600': n.type === 'error',
                    'bg-yellow-500': n.type === 'warning'
                }"
                x-text="n.message">
            </div>
        </template>
    </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('transaction-success', (event) => {
                console.log('Event:', event);
                let id = event[0]?.transactionId || event?.transactionId || event[0];
                if(id) {
                    window.open('/kasir/transaction/' + id + '/struk', '_blank');
                }
            });
        });
    </script>
</div>
</div>