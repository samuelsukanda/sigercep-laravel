<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'admin_helpdesk' => ['create', 'read'],
            'helpdesk' => ['create', 'read'],
            'laporan' => ['create', 'read'],
            'bank_ilmu' => ['create', 'read', 'update', 'delete'],
            'komplain_ipsrs' => ['create', 'read', 'update', 'delete'],
            'komplain_outsourcing_vendor' => ['create', 'read', 'update', 'delete'],
            'komplain_kesehatan_lingkungan' => ['create', 'read', 'update', 'delete'],
            'reservasi_ruangan' => ['create', 'read', 'update', 'delete'],
            'reservasi_kendaraan' => ['create', 'read', 'update', 'delete'],
            'desain_grafis' => ['create', 'read', 'update', 'delete'],
            'kecelakaan_kerja' => ['create', 'read', 'update', 'delete'],
            'komite_mutu' => ['create', 'read', 'update', 'delete'],
            'komite_bank_spo' => ['create', 'read', 'update', 'delete'],
            'komite_manajemen_risiko' => ['create', 'read', 'update', 'delete'],
            'komite_pelaporan_ikp' => ['create', 'read', 'update', 'delete'],
            'komite_pengajuan_dokumen' => ['create', 'read', 'update', 'delete'],
            'komite_laporan_perilaku' => ['create', 'read', 'update', 'delete'],
            'sdm_utw' => ['create', 'read', 'update', 'delete'],
            'sdm_struktur_organisasi' => ['read'],
            'sdm_peraturan_perusahaan' => ['create', 'read', 'update', 'delete'],
            'sdm_surat_keputusan' => ['create', 'read', 'update', 'delete'],
            'sdm_mandatory_training' => ['create', 'read', 'update', 'delete'],
            'pengadaan_peminjaman_aset' => ['create', 'read', 'update', 'delete'],
            'pengadaan_pengembalian_aset' => ['create', 'read', 'update', 'delete'],
            'pengadaan_pemindahan_aset' => ['create', 'read', 'update', 'delete'],
            'pengadaan_laporan_aset_rusak' => ['create', 'read', 'update', 'delete'],
            'komite_medik' => ['create', 'read', 'update', 'delete'],
            'kesiapan_ambulance' => ['create', 'read', 'update', 'delete'],
            'toner' => ['create', 'read', 'update', 'delete'],
            'visitasi' => ['create', 'read', 'update', 'delete'],
            'peminjaman' => ['create', 'read', 'update', 'delete'],
            'hardware' => ['create', 'read', 'update', 'delete'],
        ];

        // create permissions
        foreach ($permissions as $menu => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "$menu.$action"
                ]);
            }
        }

        // roles
        $roles = [
            'superadmin',
            'admin',
            'user',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
