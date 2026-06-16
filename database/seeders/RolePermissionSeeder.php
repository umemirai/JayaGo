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
        $kasir      = Role::firstOrCreate(['name' => 'kasir']);
        $gudang     = Role::firstOrCreate(['name' => 'pegawai_gudang']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $manajer    = Role::firstOrCreate(['name' => 'manajer']);

        $kasirPermissions = [
            'pos.access', 'transaction.create', 'transaction.view',
            'shift.open', 'shift.close', 'shift.view', 'product.search',
        ];
        $gudangPermissions = [
            'stock.update', 'stock.mutation', 'stock.opname',
            'product.view', 'product.create', 'product.edit', 'notification.view',
        ];
        $supervisorPermissions = [
            'supervisor.dashboard', 'void.authorize', 'anti_fraud.monitor',
        ];

        foreach (array_merge($kasirPermissions, $gudangPermissions, $supervisorPermissions) as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $kasir->syncPermissions($kasirPermissions);
        $gudang->syncPermissions($gudangPermissions);
        $supervisor->syncPermissions($supervisorPermissions);

        $cabang1 = \App\Models\Branch::firstOrCreate(
            ['name' => 'Cabang Jakarta'],
            ['address' => 'Jl. Sudirman No. 1, Jakarta', 'phone' => '021-1111111']
        );

        User::firstOrCreate(
            ['email' => 'kasir@minimarket.test'],
            ['name' => 'Budi Kasir', 'password' => bcrypt('password')]
        )->syncRoles(['kasir']);

        User::firstOrCreate(
            ['email' => 'gudang@minimarket.test'],
            ['name' => 'Ani Gudang', 'password' => bcrypt('password')]
        )->syncRoles(['pegawai_gudang']);

        User::firstOrCreate(
            ['email' => 'supervisor@minimarket.test'],
            ['name' => 'Alex Supervisor', 'password' => bcrypt('password')]
        )->syncRoles(['supervisor']);

        User::firstOrCreate(
            ['email' => 'manajer.jakarta@jayago.com'],
            ['name' => 'Manajer Jakarta', 'password' => bcrypt('password'), 'branch_id' => $cabang1->id]
        )->syncRoles(['manajer']);

        User::firstOrCreate(
            ['email' => 'kasir.jakarta@jayago.com'],
            ['name' => 'Kasir Jakarta', 'password' => bcrypt('password'), 'branch_id' => $cabang1->id]
        )->syncRoles(['kasir']);
    }
}