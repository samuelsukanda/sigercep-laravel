<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function canAccess($menu, $action)
    {
        $user = Auth::user();
        if (!$user) return false;

        $username = strtolower($user->username);
        $level = strtolower($user->level);

        // 🟢 SUPERADMIN → akses penuh ke semua menu
        if ($level === 'superadmin') {
            return true;
        }

        // 🛑 MENU KHUSUS hanya bisa diakses penuh oleh superadmin
        $superadminOnlyMenus = ['toner', 'visitasi', 'peminjaman', 'hardware'];
        if (in_array($menu, $superadminOnlyMenus)) {
            return $action === 'read';
        }

        // 🟠 USER HAMORI
        $hamoriMenus = [
            'komplain_ipsrs', 'kesehatan_lingkungan', 'outsourcing_vendor',
            'reservasi_ruangan', 'reservasi_kendaraan', 'desain_grafis',
            'kecelakaan_kerja', 'mutu', 'manajemen_risiko', 'peminjaman_aset',
            'pengembalian_aset', 'laporan_aset-rusak', 'pemindahan_aset', 'kesiapan_ambulance'
        ];

        if ($username === 'hamori') {
            if (in_array($menu, $hamoriMenus)) {
                return in_array($action, ['create', 'read']);
            }
            return $action === 'read';
        }

        // 🟡 ADMIN DENGAN AKSES KHUSUS
        $adminSpecificAccess = [
            'mutu' => ['bank_spo', 'mutu', 'manajemen_risiko', 'pelaporan_ikp', 'pengajuan_dokumen'],
            'sdm'  => ['utw', 'peraturan_perusahaan', 'surat_keputusan', 'mandatory_training'],
            'komdik' => ['komite_medik'],
        ];

        // Jika admin memiliki akses penuh ke menu sesuai bagiannya
        if (isset($adminSpecificAccess[$username]) && in_array($menu, $adminSpecificAccess[$username])) {
            return in_array($action, ['create', 'read', 'update', 'delete']);
        }

        // 🔵 SEMUA ADMIN (selain hamori)
        if ($level === 'admin') {
            // Menu yang restricted hanya untuk admin tertentu
            $restrictedMenus = [
                'bank_spo', 'mutu', 'manajemen_risiko', 'pelaporan_ikp', 'pengajuan_dokumen', // hanya mutu
                'utw', 'peraturan_perusahaan', 'surat_keputusan', 'mandatory_training', // hanya sdm
                'komite_medik', // hanya komdik
            ];

            // Jika menu termasuk restricted, hanya boleh read
            if (in_array($menu, $restrictedMenus)) {
                return $action === 'read';
            }

            // Admin biasa bisa create + read di semua menu lain
            return in_array($action, ['create', 'read']);
        }

        // 🔘 DEFAULT: hanya read
        return $action === 'read';
    }
}
