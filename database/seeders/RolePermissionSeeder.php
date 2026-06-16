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
        // Bersihkan cache Spatie terlebih dahulu agar tidak bentrok
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Role
        $owner      = Role::create(['name' => 'owner']);
        $manajer    = Role::create(['name' => 'manajer']);
        $kasir      = Role::create(['name' => 'kasir']);
        $gudang     = Role::create(['name' => 'pegawai_gudang']);
        $supervisor = Role::create(['name' => 'supervisor']);

        // Permission Manajer
        $manajerPermissions = [
            'dashboard.view',
            'report.view',
        ];

        // Permission Kasir
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

        // Create Permissions ke Database
        foreach ($manajerPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }
        foreach ($kasirPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }
        foreach ($gudangPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }
        foreach ($supervisorPermissions as $perm) {
            Permission::create(['name' => $perm]);
        foreach (array_merge($kasirPermissions, $gudangPermissions, $supervisorPermissions) as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Sinkronisasi Permission ke Masing-masing Role
        $manajer->syncPermissions($manajerPermissions);
        $kasir->syncPermissions($kasirPermissions);
        $gudang->syncPermissions($gudangPermissions);
        $supervisor->syncPermissions($supervisorPermissions);

        // --- BUAT AKUN DEMO LAMA ---
        $userKasir = User::create([
            'name' => 'Budi Kasir',
            'email' => 'kasir@minimarket.test',
            'password' => bcrypt('password'),
        ]);
        $userKasir->assignRole('kasir');
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

        $userSupervisor = User::create([
            'name' => 'Alex Supervisor',
            'email' => 'supervisor@minimarket.test',
            'password' => bcrypt('password'),
        ]);
        $userSupervisor->assignRole('supervisor');

        // --- BUAT DATA CABANG & AKUN TIM MANAJER ---
        $cabang1 = \App\Models\Branch::create([
            'name'    => 'Cabang Jakarta',
            'address' => 'Jl. Sudirman No. 1, Jakarta',
            'phone'   => '021-1111111',
        ]);

        // Akun Manajer Toko (Sudah sinkron Spatie + Kolom Text)
        $userManajer = User::create([
            'name'      => 'Manajer Jakarta',
            'email'     => 'manajer.jakarta@jayago.com',
            'password'  => bcrypt('password'),
            'branch_id' => $cabang1->id,
            'role'      => 'manajer',
        ]);
        $userManajer->assignRole('manajer');

        // Akun Kasir Jakarta (Sudah sinkron Spatie + Kolom Text)
        $userKasirJkt = User::create([
            'name'      => 'Kasir Jakarta',
            'email'     => 'kasir.jakarta@jayago.com',
            'password'  => bcrypt('password'),
            'branch_id' => $cabang1->id,
            'role'      => 'kasir',
        ]);
        $userKasirJkt->assignRole('kasir');

        $userOwner = User::create([
            'name'      => 'Pak Jayusman (Owner)',
            'email'     => 'owner@jayago.com',
            'password'  => bcrypt('owner123'), // Password untuk login Owner
            'role'      => 'owner',
        ]);
        $userOwner->assignRole('owner');
        User::firstOrCreate(
            ['email' => 'kasir.jakarta@jayago.com'],
            ['name' => 'Kasir Jakarta', 'password' => bcrypt('password'), 'branch_id' => $cabang1->id]
        )->syncRoles(['kasir']);
    }
}