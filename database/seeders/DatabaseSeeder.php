<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        // Buat akun demo
        $userKasir = User::create([
            'name' => 'Budi Kasir',
            'email' => 'kasir@minimarket.test',
            'password' => bcrypt('password'),
        ]);
        $userKasir->assignRole('kasir');

        $userGudang = User::create([
            'name' => 'Ani Gudang',
            'email' => 'gudang@minimarket.test',
            'password' => bcrypt('password'),
        ]);
        $userGudang->assignRole('pegawai_gudang');

        // Akun Manajer (dari bagian Manajer Toko)
        $cabang1 = \App\Models\Branch::where('name', 'Cabang Jakarta')->first();
                    if (!$cabang1) {
                $cabang1 = \App\Models\Branch::create([
                 'name'    => 'Cabang Jakarta',
                 'address' => 'Jl. Sudirman No. 1, Jakarta',
                 'phone'   => '021-1111111',
                ]);
}

        $userManajer = User::create([
            'name'      => 'Manajer Jakarta',
            'email'     => 'manajer.jakarta@jayago.com',
           'password'  => bcrypt('password'),
           'branch_id' => $cabang1->id,
             'role'      => 'manajer',
            ]);

        $userKasirJkt = User::create([
            'name'      => 'Kasir Jakarta',
            'email'     => 'kasir.jakarta@jayago.com',
            'password'  => bcrypt('password'),
            'branch_id' => $cabang1->id,
            'role'      => 'kasir',
            ]);
    }
    
}
