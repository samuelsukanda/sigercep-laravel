<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\PermissionRule;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // CLEAN TABLE
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PermissionRule::truncate();
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // HELPER SUPERADMIN RULE
        $superRules = [
            ['unit' => 'teknologi informasi', 'jabatan' => 'operasional it technical support', 'name' => 'sammuel'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'operasional it technical support', 'name' => 'raden.ibnu'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'it infrastruktur', 'name' => 'iyan.hermawan'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'pengembangan sistem', 'name' => 'novit.adriansyah'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'spv it', 'name' => 'deden eka nugraha'],
        ];

        // SUPERADMIN (FULL AKSES)
        $super = Permission::create([
            'menu' => '*',
            'action' => '*'
        ]);
        $super->rules()->createMany($superRules);

        // DAFTAR SEMUA MENU
        $allMenus = [
            'komplain_ipsrs',
            'kesehatan_lingkungan',
            'outsourcing_vendor',
            'reservasi_ruangan',
            'reservasi_kendaraan',
            'desain_grafis',
            'kecelakaan_kerja',
            'kesiapan_ambulance',
            'mutu',
            'manajemen_risiko',
            'pelaporan_ikp',
            'pengajuan_dokumen',
            'bank_ilmu',
            'laporan_perilaku',
            'peminjaman_aset',
            'pengembalian_aset',
            'laporan_aset_rusak',
            'pemindahan_aset',
            'helpdesk'
        ];

        // SEMUA USER (HANYA CREATE DAN READ)
        $basicActions = ['create', 'read'];

        foreach ($allMenus as $menu) {
            foreach ($basicActions as $action) {
                Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ])->rules()->create([
                    'unit' => null,
                    'jabatan' => null,
                    'name' => null
                ]);
            }
        }

        // READ ONLY MENU (TANPA CREATE, UPDATE, DELETE)
        $readOnlyMenus = [
            'bank_spo',
            'utw',
            'peraturan_perusahaan',
            'surat_keputusan',
            'mandatory_training',
            'komite_medik',
        ];

        foreach ($readOnlyMenus as $menu) {
            Permission::create([
                'menu' => $menu,
                'action' => 'read'
            ])->rules()->create([
                'unit' => null,
                'jabatan' => null,
                'name' => null
            ]);
        }

        // ADMIN HELP DESK (IT) - FULL AKSES
        $fullActions = ['create', 'read', 'update', 'delete'];

        foreach ($fullActions as $action) {
            Permission::create([
                'menu' => 'helpdesk',
                'action' => $action
            ])->rules()->create([
                'unit' => 'teknologi informasi'
            ]);
        }

        // REPORT - READ ONLY UNTUK UNIT IT
        Permission::create([
            'menu' => 'reports',
            'action' => 'read'
        ])->rules()->create([
            'unit' => 'teknologi informasi'
        ]);

        // MUTU - FULL AKSES UNTUK MENU TERTENTU
        $mutuMenus = [
            'mutu',
            'bank_spo',
            'manajemen_risiko',
            'pelaporan_ikp',
            'pengajuan_dokumen',
            'bank_ilmu',
            'laporan_perilaku'
        ];

        $mutuRules = [
            ['unit' => 'mutu', 'jabatan' => 'ketua mutu', 'name' => 'pupu.pujiawati'],
            ['unit' => 'mutu', 'jabatan' => 'staf mutu', 'name' => 'indah.pertiwi'],
        ];

        foreach ($mutuMenus as $menu) {
            foreach ($fullActions as $action) {
                $permission = Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ]);
                $permission->rules()->createMany($mutuRules);
            }
        }

        // SDM - FULL AKSES UNTUK MENU TERTENTU
        $sdmMenus = [
            'utw',
            'peraturan_perusahaan',
            'surat_keputusan',
            'mandatory_training'
        ];

        $sdmRules = [
            ['unit' => 'sdm', 'jabatan' => 'manajer sdm dan hukum', 'name' => 'jatu.priya'],
            ['unit' => 'sdm', 'jabatan' => 'spv sdm dan hukum', 'name' => 'ruri.kemala'],
            ['unit' => 'sdm', 'jabatan' => 'staf sdm', 'name' => 'novia.firstania'],
            ['unit' => 'sdm', 'jabatan' => 'staf diklat dan pengembangan', 'name' => 'rifaldi.zakhari'],
            ['unit' => 'sdm', 'jabatan' => 'staf hukum dan hubungan industrial', 'name' => 'muhamad.fajar'],
        ];

        foreach ($sdmMenus as $menu) {
            foreach ($fullActions as $action) {
                $permission = Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ]);
                $permission->rules()->createMany($sdmRules);
            }
        }

        // KOMITE MEDIK - FULL AKSES (TIM KHUSUS)
        $komiteRules = [
            ['unit' => 'komite medik', 'jabatan' => 'staf komite medik', 'name' => 'meliana.fatimah']
        ];

        foreach ($fullActions as $action) {
            $permission = Permission::create([
                'menu' => 'komite_medik',
                'action' => $action
            ]);
            $permission->rules()->createMany($komiteRules);
        }
        
        // PERMISSION MANAGEMENT - HANYA UNTUK SAMMUEL
        $permManagementActions = ['read', 'create', 'update', 'delete'];

        foreach ($permManagementActions as $action) {
            $permission = Permission::create([
                'menu' => 'permissions',
                'action' => $action
            ]);

            $permission->rules()->create([
                'unit' => 'teknologi informasi',
                'jabatan' => 'operasional it technical support',
                'name' => 'sammuel'
            ]);
        }

        // SUPERADMIN ONLY MENU (HANYA SUPERADMIN YANG BISA)
        $superOnlyMenus = ['toner', 'visitasi', 'hardware', 'peminjaman'];

        foreach ($superOnlyMenus as $menu) {
            foreach ($fullActions as $action) {
                $permission = Permission::create([
                    'menu' => $menu,
                    'action' => $action
                ]);
                $permission->rules()->createMany($superRules);
            }
        }
    }
}
