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
        $kasir     = Role::create(['name' => 'kasir']);
        $gudang    = Role::create(['name' => 'pegawai_gudang']);
        $supervisor = Role::create(['name' => 'supervisor']);

        // Permission Kasir
        $kasirPermissions = [
            'pos.access',
            'transaction.create',
            'transaction.view',
            'shift.open',
            'shift.close',
            'shift.view',
            'product.search',
        ];

        // Permission Gudang
        $gudangPermissions = [
            'stock.update',
            'stock.mutation',
            'stock.opname',
            'product.view',
            'product.create',
            'product.edit',
            'notification.view',
        ];

        // Permission Supervisor
        $supervisorPermissions = [
            'supervisor.dashboard',
            'void.authorize',
            'anti_fraud.monitor',
        ];


        foreach ($kasirPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }
        foreach ($gudangPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }
        foreach ($supervisorPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $kasir->syncPermissions($kasirPermissions);
        $gudang->syncPermissions($gudangPermissions);
        $supervisor->syncPermissions($supervisorPermissions);

        // Buat Akun Demo
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

        $userSupervisor = User::create([
            'name' => 'Alex Supervisor',
            'email' => 'supervisor@minimarket.test',
            'password' => bcrypt('password'),
        ]);
        $userSupervisor->assignRole('supervisor');
    }
}
