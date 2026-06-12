<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $transaction->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 80mm; }

        .header { text-align: center; padding: 10px 0; border-bottom: 1px dashed #000; margin-bottom: 8px; }
        .header h2 { font-size: 16px; font-weight: bold; }
        .header p { font-size: 10px; margin-top: 2px; }

        .info { margin-bottom: 8px; font-size: 10px; }
        .info span { display: inline-block; width: 50%; }

        .items { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .items td { padding: 2px 0; vertical-align: top; }
        .items .name { width: 55%; }
        .items .qty { width: 10%; text-align: center; }
        .items .price { width: 35%; text-align: right; }

        .divider { border-top: 1px dashed #000; margin: 8px 0; }

        .totals td { padding: 2px 0; }
        .totals .label { width: 55%; }
        .totals .value { width: 45%; text-align: right; }
        .totals .grand td { font-weight: bold; font-size: 14px; }

        .footer { text-align: center; margin-top: 12px; font-size: 10px; border-top: 1px dashed #000; padding-top: 8px; }

        @media print {
            @page { margin: 0; size: 80mm auto; }
            body { width: 80mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding:10px; background:#f0f0f0; margin-bottom:10px; text-align:center;">
        <button onclick="window.print()" style="padding:8px 20px; background:#2563eb; color:white; border:none; border-radius:6px; cursor:pointer; font-size:14px;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()" style="padding:8px 20px; background:#6b7280; color:white; border:none; border-radius:6px; cursor:pointer; font-size:14px; margin-left:8px;">
            ✕ Tutup
        </button>
    </div>

    <div class="header">
        <h2>MINIMARKET JAYMART</h2>
        <p>Jl. Contoh No. 123, Bandung</p>
        <p>Telp: (022) 1234567</p>
    </div>

    <div class="info">
        <div><span>No:</span><span>{{ $transaction->invoice_number }}</span></div>
        <div><span>Tanggal:</span><span>{{ $transaction->transaction_date->format('d/m/Y H:i') }}</span></div>
        <div><span>Kasir:</span><span>{{ $transaction->cashier->name }}</span></div>
        <div><span>Bayar:</span><span>{{ strtoupper($transaction->payment_method) }}</span></div>
    </div>

    <div class="divider"></div>

    <table class="items">
        <tbody>
            @foreach($transaction->items as $item)
            <tr>
                <td class="name">{{ $item->product_name }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="name" style="padding-left:8px; color:#555;">
                    {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}
                </td>
                <td class="qty"></td>
                <td class="price">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals" style="width:100%">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->discount > 0)
        <tr>
            <td class="label">Diskon</td>
            <td class="value">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="grand">
            <td class="label">TOTAL</td>
            <td class="value">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Bayar</td>
            <td class="value">Rp {{ number_format($transaction->paid, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Kembali</td>
            <td class="value">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('transaction-success', (event) => {
            window.open('/kasir/transaction/' + event.transactionId + '/struk', '_blank');
        });
    });
    </script>
</body>
</html>