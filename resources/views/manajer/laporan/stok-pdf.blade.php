<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok — {{ $namaCabang }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e1e2d;
            background: #fff;
            padding: 30px 36px;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #16a34a;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .header-left .company {
            font-size: 20px;
            font-weight: 700;
            color: #16a34a;
            letter-spacing: 0.5px;
        }

        .header-left .subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        .header-right {
            text-align: right;
        }

        .header-right .doc-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .header-right .meta {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
            line-height: 1.6;
        }

        /* ── SUMMARY BOXES ── */
        .summary {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .summary-box {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 14px;
            background: #f9fafb;
        }

        .summary-box .label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-box .value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-top: 2px;
        }

        .summary-box.danger .value {
            color: #dc2626;
        }

        .summary-box.safe .value {
            color: #16a34a;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        thead tr {
            background: #16a34a;
            color: #fff;
        }

        thead th {
            padding: 9px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        thead th.right {
            text-align: right;
        }

        tbody tr:nth-child(even) {
            background: #f0fdf4;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody tr.menipis {
            background: #fff1f2 !important;
        }

        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
            vertical-align: middle;
        }

        tbody td.right {
            text-align: right;
        }

        tbody td.mono {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 9px;
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 600;
        }

        .badge-aman {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-menipis {
            background: #fee2e2;
            color: #b91c1c;
        }

        .stok-menipis {
            color: #dc2626;
            font-weight: 700;
        }

        /* ── FOOTER ── */
        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #9ca3af;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <div class="company">Jaygo</div>
            <div class="subtitle">Cabang {{ $namaCabang }}</div>
        </div>
        <div class="header-right">
            <div class="doc-title">Laporan Stok Produk</div>
            <div class="meta">
                Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB<br>
                Total Produk: {{ $produk->count() }} item
            </div>
        </div>
    </div>

    <!-- SUMMARY -->
    @php
        $totalProduk  = $produk->count();
        $stokAman     = $produk->filter(fn($p) => $p->stock > $p->stock_minimum)->count();
        $stokMenipis  = $produk->filter(fn($p) => $p->stock <= $p->stock_minimum)->count();
        $totalNilai   = $produk->sum(fn($p) => $p->stock * $p->price);
    @endphp

    <div class="summary">
        <div class="summary-box">
            <div class="label">Total Produk</div>
            <div class="value">{{ $totalProduk }}</div>
        </div>
        <div class="summary-box safe">
            <div class="label">Stok Aman</div>
            <div class="value">{{ $stokAman }}</div>
        </div>
        <div class="summary-box danger">
            <div class="label">Stok Menipis</div>
            <div class="value">{{ $stokMenipis }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Total Nilai Stok</div>
            <div class="value" style="font-size:13px;">Rp {{ number_format($totalNilai, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- TABLE -->
    @if($produk->isEmpty())
        <div class="no-data">Belum ada data produk.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:4%">#</th>
                    <th style="width:13%">Barcode</th>
                    <th style="width:28%">Nama Produk</th>
                    <th style="width:14%">Kategori</th>
                    <th class="right" style="width:14%">Harga</th>
                    <th class="right" style="width:8%">Stok</th>
                    <th class="right" style="width:10%">Min. Stok</th>
                    <th style="width:9%; text-align:center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produk as $i => $p)
                @php $menipis = $p->stock <= $p->stock_minimum; @endphp
                <tr class="{{ $menipis ? 'menipis' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td class="mono">{{ $p->barcode }}</td>
                    <td style="font-weight: 600;">{{ $p->name }}</td>
                    <td style="color:#6b7280">{{ $p->category }}</td>
                    <td class="right">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td class="right {{ $menipis ? 'stok-menipis' : '' }}">{{ $p->stock }}</td>
                    <td class="right" style="color:#6b7280">{{ $p->stock_minimum }}</td>
                    <td style="text-align:center">
                        @if($menipis)
                            <span class="badge badge-menipis">Menipis</span>
                        @else
                            <span class="badge badge-aman">Aman</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        <span>Dicetak oleh sistem &mdash; Jaygo {{ $namaCabang }}</span>
        <span>{{ now()->format('d/m/Y H:i') }} WIB</span>
    </div>

</body>
</html>