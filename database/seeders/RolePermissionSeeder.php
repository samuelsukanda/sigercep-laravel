<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Menu default semua user
        $defaultMenus = [
            'helpdesk',
            'bank_ilmu',
            'komplain_ipsrs',
            'komplain_outsourcing_vendor',
            'komplain_kesehatan_lingkungan',
            'reservasi_ruangan',
            'reservasi_kendaraan',
            'desain_grafis',
            'kecelakaan_kerja',
            'komite_mutu',
            'komite_manajemen_risiko',
            'komite_pelaporan_ikp',
            'komite_pengajuan_dokumen',
            'komite_laporan_perilaku',
            'sdm_struktur_organisasi',
            'pengadaan_peminjaman_aset',
            'pengadaan_pengembalian_aset',
            'pengadaan_pemindahan_aset',
            'pengadaan_laporan_aset_rusak',
            'kesiapan_ambulance'
        ];

        // Menu superadmin only
        $superadminOnlyMenus = ['toner', 'visitasi', 'peminjaman', 'hardware', 'admin_helpdesk', 'laporan'];

        // Menu user spesific
        $unitSpecificMenus = ['komite_bank_spo', 'sdm_utw', 'sdm_peraturan_perusahaan', 'sdm_surat_keputusan', 'sdm_mandatory_training', 'komite_medik'];

        // Buat semua permission default
        foreach ($defaultMenus as $menu) {
            foreach (['create', 'read'] as $action) {
                Permission::firstOrCreate(['name' => "$menu.$action"]);
            }
        }

        // Buat permission superadmin only
        foreach ($superadminOnlyMenus as $menu) {
            foreach (['create', 'read', 'update', 'delete'] as $action) {
                Permission::firstOrCreate(['name' => "$menu.$action"]);
            }
        }

        // Buat permission user spesific
        foreach ($unitSpecificMenus as $menu) {
            foreach (['create', 'read', 'update', 'delete'] as $action) {
                Permission::firstOrCreate(['name' => "$menu.$action"]);
            }
        }

        // Buat role
        $roles = ['superadmin', 'admin', 'user'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Assign permission per role

        // Superadmin → semua permission
        Role::findByName('superadmin')->givePermissionTo(Permission::all());

        // Admin / User → hanya default menu
        $defaultPerms = [];
        foreach ($defaultMenus as $menu) {
            $defaultPerms[] = "$menu.create";
            $defaultPerms[] = "$menu.read";
        }

        Role::findByName('admin')->givePermissionTo($defaultPerms);
        Role::findByName('user')->givePermissionTo($defaultPerms);
    }
}
