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
        // ===============================
        // 🔥 CLEAN TABLE (WAJIB)
        // ===============================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PermissionRule::truncate();
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ===============================
        // 🔧 HELPER SUPERADMIN RULE
        // ===============================
        $superRules = [
            ['unit' => 'teknologi informasi', 'jabatan' => 'operasional it technical support', 'name' => 'sammuel'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'operasional it technical support', 'name' => 'raden.ibnu'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'it infrastruktur', 'name' => 'iyan.hermawan'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'pengembangan sistem', 'name' => 'novit.adriansyah'],
            ['unit' => 'teknologi informasi', 'jabatan' => 'spv it', 'name' => 'deden eka nugraha'],
        ];

        // ===============================
        // 🟢 SUPERADMIN (FULL AKSES)
        // ===============================
        $super = Permission::create([
            'menu' => '*',
            'action' => '*'
        ]);

        $super->rules()->createMany($superRules);

        // ===============================
        // 🔴 SUPERADMIN ONLY MENU
        // ===============================
        $superMenus = ['toner', 'visitasi', 'hardware', 'peminjaman'];

        foreach ($superMenus as $menu) {

            $p = Permission::create([
                'menu' => $menu,
                'action' => 'manage'
            ]);
            $p->rules()->createMany($superRules);

            Permission::create([
                'menu' => $menu,
                'action' => 'read'
            ])->rules()->createMany($superRules);
        }

        // ===============================
        // 🟠 SEMUA USER (CREATE + READ)
        // ===============================
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

        foreach ($allMenus as $menu) {

            // CREATE
            Permission::create([
                'menu' => $menu,
                'action' => 'create'
            ])->rules()->create([
                'unit' => null,
                'jabatan' => null,
                'name' => null
            ]);

            // READ
            Permission::create([
                'menu' => $menu,
                'action' => 'read'
            ])->rules()->create([
                'unit' => null,
                'jabatan' => null,
                'name' => null
            ]);
        }

        // ===============================
        // 🟢 SEMUA USER (READ ONLY)
        // ===============================
        $readOnlyMenus = [
            'bank_spo',
            'utw',
            'peraturan_perusahaan',
            'surat_keputusan',
            'mandatory_training'
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

        // ===============================
        // 🔵 ADMIN HELP DESK (IT)
        // ===============================
        Permission::create([
            'menu' => 'helpdesk',
            'action' => 'manage'
        ])->rules()->create([
            'unit' => 'teknologi informasi'
        ]);

        // ===============================
        // 🟡 REPORT
        // ===============================
        Permission::create([
            'menu' => 'reports',
            'action' => 'read'
        ])->rules()->create([
            'unit' => 'teknologi informasi'
        ]);

        // ===============================
        // 🔴 MUTU
        // ===============================
        $mutuMenus = [
            'mutu',
            'bank_spo',
            'manajemen_risiko',
            'pelaporan_ikp',
            'pengajuan_dokumen',
            'bank_ilmu',
            'laporan_perilaku'
        ];

        foreach ($mutuMenus as $menu) {
            Permission::create([
                'menu' => $menu,
                'action' => 'manage'
            ])->rules()->createMany([
                ['unit' => 'mutu', 'jabatan' => 'ketua mutu', 'name' => 'pupu.pujiawati'],
                ['unit' => 'mutu', 'jabatan' => 'staf mutu', 'name' => 'indah.pertiwi'],
            ]);
        }

        // ===============================
        // 🟣 SDM
        // ===============================
        $sdmMenus = [
            'utw',
            'peraturan_perusahaan',
            'surat_keputusan',
            'mandatory_training'
        ];

        foreach ($sdmMenus as $menu) {
            Permission::create([
                'menu' => $menu,
                'action' => 'manage'
            ])->rules()->createMany([
                ['unit' => 'sdm', 'jabatan' => 'manajer sdm dan hukum', 'name' => 'jatu.priya'],
                ['unit' => 'sdm', 'jabatan' => 'spv sdm dan hukum', 'name' => 'ruri.kemala'],
                ['unit' => 'sdm', 'jabatan' => 'staf sdm', 'name' => 'novia.firstania'],
                ['unit' => 'sdm', 'jabatan' => 'staf diklat dan pengembangan', 'name' => 'rifaldi.zakhari'],
                ['unit' => 'sdm', 'jabatan' => 'staf hukum dan hubungan industrial', 'name' => 'muhamad.fajar'],
            ]);
        }

        // ===============================
        // 🟤 KOMITE MEDIK
        // ===============================
        Permission::create([
            'menu' => 'komite_medik',
            'action' => 'manage'
        ])->rules()->create([
            'unit' => 'komite medik',
            'jabatan' => 'staf komite medik',
            'name' => 'meliana.fatimah'
        ]);
    }
}