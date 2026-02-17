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
        $role = strtolower($user->role);

        // 🟢 SUPERADMIN → akses penuh ke semua menu
        if ($role === 'superadmin') {
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
            'reservasi_ruangan', 'reservasi_kendaraan', 
            'desain_grafis', 'kecelakaan_kerja','kesiapan_ambulance',
            'mutu', 'manajemen_risiko', 'pelaporan_ikp', 'pengajuan_dokumen', 'bank_ilmu', 'laporan_perilaku', 
            'peminjaman_aset', 'pengembalian_aset', 'laporan_aset_rusak', 'pemindahan_aset',
        ];

        if ($username === 'hamori') {
            if (in_array($menu, $hamoriMenus)) {
                return in_array($action, ['create', 'read']);
            }
            return $action === 'read';
        }

        // 🟡 ADMIN DENGAN AKSES KHUSUS
        $adminSpecificAccess = [
            'mutu' => ['mutu', 'bank_spo', 'manajemen_risiko', 'pelaporan_ikp', 'pengajuan_dokumen', 'bank_ilmu', 'laporan_perilaku'],
            'sdm'  => ['utw', 'peraturan_perusahaan', 'surat_keputusan', 'mandatory_training'],
            'komdik' => ['komite_medik'],
            'ipsrs' => ['komplain_ipsrs', 'kesehatan_lingkungan', 'outsourcing_vendor'],
            'desaingrafis' => ['desain_grafis'],
        ];

        // Jika admin memiliki akses penuh ke menu sesuai bagiannya
        if (isset($adminSpecificAccess[$username]) && in_array($menu, $adminSpecificAccess[$username])) {
            return in_array($action, ['create', 'read', 'update', 'delete']);
        }

        // 🔵 SEMUA ADMIN (selain hamori)
        if ($role === 'admin') {
            // Menu yang restricted hanya untuk admin tertentu
            $restrictedMenus = [
                'bank_spo', // hanya mutu
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
