<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $cabang = Branch::where('name', 'Cabang Jakarta')->first();
        $kasir  = User::where('email', 'kasir.jakarta@jayago.com')->first();

        if (!$cabang || !$kasir) {
            $this->command->error('Jalankan RolePermissionSeeder dulu!');
            return;
        }

        // ── PRODUK ──────────────────────────────────────────────
        $produkList = [
            ['barcode' => '8991234560001', 'name' => 'Indomie Goreng',        'category' => 'Makanan',   'price' => 3500,   'stock' => 150, 'stock_minimum' => 20],
            ['barcode' => '8991234560002', 'name' => 'Aqua 600ml',            'category' => 'Minuman',   'price' => 4000,   'stock' => 200, 'stock_minimum' => 30],
            ['barcode' => '8991234560003', 'name' => 'Teh Botol Sosro 350ml', 'category' => 'Minuman',   'price' => 5000,   'stock' => 8,   'stock_minimum' => 20],
            ['barcode' => '8991234560004', 'name' => 'Beng-Beng',             'category' => 'Snack',     'price' => 3000,   'stock' => 80,  'stock_minimum' => 15],
            ['barcode' => '8991234560005', 'name' => 'Pocari Sweat 500ml',    'category' => 'Minuman',   'price' => 8500,   'stock' => 5,   'stock_minimum' => 20],
            ['barcode' => '8991234560006', 'name' => 'Sabun Lifebuoy',        'category' => 'Kebersihan','price' => 6500,   'stock' => 60,  'stock_minimum' => 10],
            ['barcode' => '8991234560007', 'name' => 'Shampo Pantene 170ml',  'category' => 'Kebersihan','price' => 18000,  'stock' => 3,   'stock_minimum' => 10],
            ['barcode' => '8991234560008', 'name' => 'Roti Tawar Sari Roti',  'category' => 'Makanan',   'price' => 14000,  'stock' => 25,  'stock_minimum' => 10],
            ['barcode' => '8991234560009', 'name' => 'Susu Ultra 200ml',      'category' => 'Minuman',   'price' => 5500,   'stock' => 90,  'stock_minimum' => 20],
            ['barcode' => '8991234560010', 'name' => 'Chitato 68gr',          'category' => 'Snack',     'price' => 10000,  'stock' => 45,  'stock_minimum' => 15],
            ['barcode' => '8991234560011', 'name' => 'Minyak Goreng Bimoli 1L','category' => 'Dapur',    'price' => 22000,  'stock' => 30,  'stock_minimum' => 10],
            ['barcode' => '8991234560012', 'name' => 'Gula Pasir 1kg',        'category' => 'Dapur',     'price' => 16000,  'stock' => 7,   'stock_minimum' => 10],
            ['barcode' => '8991234560013', 'name' => 'Kopi Kapal Api Sachet', 'category' => 'Minuman',   'price' => 2500,   'stock' => 120, 'stock_minimum' => 25],
            ['barcode' => '8991234560014', 'name' => 'Oreo Original',         'category' => 'Snack',     'price' => 8000,   'stock' => 55,  'stock_minimum' => 15],
            ['barcode' => '8991234560015', 'name' => 'Pasta Gigi Pepsodent',  'category' => 'Kebersihan','price' => 12000,  'stock' => 40,  'stock_minimum' => 10],
        ];

        foreach ($produkList as $p) {
            Product::firstOrCreate(
                ['barcode' => $p['barcode']],
                array_merge($p, ['branch_id' => $cabang->id])
            );
        }

        $this->command->info('✅ ' . count($produkList) . ' produk berhasil dibuat.');

        // ── TRANSAKSI 7 HARI TERAKHIR ────────────────────────────
        $produkDb = Product::where('branch_id', $cabang->id)->get();

        $transaksiPerHari = [6, 9, 5, 12, 8, 15, 10]; // D-6 sampai hari ini

        $invoiceCounter = 1;

        foreach ($transaksiPerHari as $dayOffset => $jumlah) {
            $tanggal = Carbon::now()->subDays(6 - $dayOffset);

            for ($i = 0; $i < $jumlah; $i++) {
                $jamAcak = $tanggal->copy()->setTime(rand(8, 21), rand(0, 59), rand(0, 59));

                $metodePembayaran = collect(['tunai', 'tunai', 'tunai', 'qris', 'debit'])->random();

                // Pilih 2-5 produk acak
                $itemDipilih = $produkDb->random(rand(2, 5));
                $subtotal = 0;
                foreach ($itemDipilih as $prod) {
                    $qty       = rand(1, 4);
                    $subtotal += $prod->price * $qty;
                }

                // Belum ada logika diskon/pajak terpisah, jadi total = subtotal
                $total = $subtotal;

                // Uang dibayar: untuk qris/debit pas sesuai total, untuk tunai dibulatkan ke atas (ada kembalian)
                if ($metodePembayaran === 'tunai') {
                    $paid   = (int) (ceil($total / 5000) * 5000);
                    $change = $paid - $total;
                } else {
                    $paid   = $total;
                    $change = 0;
                }

                $invoiceNumber = 'INV-' . $tanggal->format('Ymd') . '-' . str_pad($invoiceCounter, 4, '0', STR_PAD_LEFT);

                Transaction::create([
                    'invoice_number'   => $invoiceNumber,
                    'branch_id'        => $cabang->id,
                    'user_id'          => $kasir->id,
                    'subtotal'         => $subtotal,
                    'total'            => $total,
                    'paid'             => $paid,
                    'change'           => $change,
                    'payment_method'   => $metodePembayaran,
                    'status'           => 'selesai',
                    'created_at'       => $jamAcak,
                    'updated_at'       => $jamAcak,
                ]);

                $invoiceCounter++;
            }
        }

        $totalTrx = array_sum($transaksiPerHari);
        $this->command->info("✅ {$totalTrx} transaksi dummy berhasil dibuat.");
    }
}