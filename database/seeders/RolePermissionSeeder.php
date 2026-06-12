<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Role
        $kasir   = Role::create(['name' => 'kasir']);
        $gudang  = Role::create(['name' => 'pegawai_gudang']);

        // Permission Kasir
        $kasirPermissions = [
            'pos.access', 'transaction.create', 'transaction.view',
            'shift.open', 'shift.close', 'shift.view',
            'product.search',
        ];

        // Permission Gudang
        $gudangPermissions = [
            'stock.update', 'stock.mutation', 'stock.opname',
            'product.view', 'product.create', 'product.edit',
            'notification.view',
        ];

        foreach ($kasirPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }
        foreach ($gudangPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $kasir->syncPermissions($kasirPermissions);
        $gudang->syncPermissions($gudangPermissions);

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
    }
}