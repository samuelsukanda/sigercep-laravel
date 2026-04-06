<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function canAccess($menu, $action)
    {
        $user = Auth::user();
        if (!$user) return false;

        $name = strtolower(trim($user->name ?? ''));
        $unit = strtolower(trim($user->unit ?? ''));
        $jabatan = strtolower(trim($user->jabatan ?? ''));

        // ===============================
        // 🟢 SUPERADMIN
        // ===============================
        $superadminUsers = [
            'sammuel',
            'raden.ibnu',
            'iyan.hermawan',
            'novit.adriansyah',
            'deden eka nugraha'
        ];

        if (
            $unit === 'teknologi informasi' &&
            in_array($jabatan, [
                'spv it',
                'operasional it technical support',
                'it infrastruktur',
                'pengembangan sistem'
            ]) &&
            in_array($name, $superadminUsers)
        ) {
            return true;
        }

        // ===============================
        // 🛑 MENU KHUSUS
        // ===============================
        $superadminOnlyMenus = ['toner', 'visitasi', 'peminjaman', 'hardware'];
        if (in_array($menu, $superadminOnlyMenus)) {
            return $action === 'read';
        }

        // ===============================
        // 🟠 SEMUA USER
        // ===============================
        $allUsers = [
            'komplain_ipsrs','kesehatan_lingkungan','outsourcing_vendor',
            'reservasi_ruangan','reservasi_kendaraan','desain_grafis',
            'kecelakaan_kerja','kesiapan_ambulance','mutu',
            'manajemen_risiko','pelaporan_ikp','pengajuan_dokumen',
            'bank_ilmu','laporan_perilaku','peminjaman_aset',
            'pengembalian_aset','laporan_aset_rusak','pemindahan_aset',
        ];

        if (in_array($menu, $allUsers)) {
            return in_array($action, ['create', 'read']);
        }

        // ===============================
        // 🟡 ADMIN SPESIFIK
        // ===============================
        $adminSpecificAccess = [
            'mutu' => [
                'unit' => ['mutu'],
                'jabatan' => ['staf mutu', 'ketua mutu'],
                'users' => ['pupu.pujiawati', 'indah.pertiwi'],
                'menus' => [
                    'mutu','bank_spo','manajemen_risiko',
                    'pelaporan_ikp','pengajuan_dokumen',
                    'bank_ilmu','laporan_perilaku'
                ]
            ],
            'sdm' => [
                'unit' => ['sdm'],
                'jabatan' => [
                    'manajer sdm dan hukum',
                    'spv sdm dan hukum',
                    'staf sdm',
                    'staf diklat dan pengembangan',
                    'staf hukum dan hubungan industrial'
                ],
                'users' => [
                    'novia.firstania',
                    'jatu.priya',
                    'rifaldi.zakhari',
                    'ruri.kemala',
                    'muhamad.fajar'
                ],
                'menus' => [
                    'utw','peraturan_perusahaan',
                    'surat_keputusan','mandatory_training'
                ]
            ],
            'komite_medik' => [
                'unit' => ['komite medik'],
                'jabatan' => ['staf komite medik'],
                'users' => ['meliana.fatimah'],
                'menus' => ['komite_medik']
            ],
        ];

        foreach ($adminSpecificAccess as $config) {
            if (
                in_array($unit, $config['unit']) &&
                in_array($jabatan, $config['jabatan']) &&
                in_array($name, $config['users']) &&
                in_array($menu, $config['menus'])
            ) {
                return in_array($action, ['create', 'read', 'update', 'delete']);
            }
        }

        // ===============================
        // 🔵 DEFAULT USER
        // ===============================
        $restrictedMenus = [
            'bank_spo',
            'utw','peraturan_perusahaan',
            'surat_keputusan','mandatory_training',
            'komite_medik',
        ];

        if (in_array($menu, $restrictedMenus)) {
            return $action === 'read';
        }

        return $action === 'read';
    }
}