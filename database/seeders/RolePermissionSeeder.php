<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar semua crud/menu
        $menus = [
            // Komplain
            'ipsrs',
            'outsourcing-vendor',
            'kesehatan-lingkungan',

            // Reservasi
            'ruangan',
            'kendaraan',

            // Desain Grafis
            'desain-grafis',

            // Kecelakaan Kerja
            'kecelakaan-kerja',

            // Komite Mutu
            'mutu',
            'bank-spo',
            'manajemen-risiko',

            // SDM & Hukum
            'utw',
            'peraturan-perusahaan',
            'surat-keputusan',
            'mandatory-training',

            // Pengadaan & Aset
            'peminjaman-aset',
            'pengembalian-aset',
            'pemindahan-aset',
            'laporan-aset-rusak',

            // Lain-lain
            'komite-medik',
            'kesiapan-ambulance',
            'toner',
            'hardware',
        ];

        // Buat semua permission CRUD
        foreach ($menus as $menu) {
            foreach (['create', 'read', 'update', 'delete'] as $action) {
                Permission::firstOrCreate(['name' => "$action $menu"]);
            }
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $admin      = Role::firstOrCreate(['name' => 'Admin']);
        $user       = Role::firstOrCreate(['name' => 'User']);

        // Superadmin dapat semua permission
        $superAdmin->syncPermissions(Permission::all());

        // User hanya bisa create & read di semua menu
        $userPermissions = Permission::where(function ($q) {
            $q->where('name', 'like', 'create%')
                ->orWhere('name', 'like', 'read%');
        })->get();
        $user->syncPermissions($userPermissions);

    }
}
