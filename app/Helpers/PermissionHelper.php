<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function canAccess($menu, $action)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->level === 'superadmin') {
            return true;
        }

        if ($user->level === 'user') {
            return in_array($action, ['create', 'read']);
        }

        $username = strtolower($user->username);

        $adminAccess = [
            'komdik'       => ['komite_medik', 'reservasi_ruangan'],
            'ipsrs'        => ['komplain_ipsrs', 'kesehatan_lingkungan', 'outsourcing_vendor'],
            'sdm'          => ['utw', 'peraturan_perusahaan', 'surat_keputusan', 'mandatory_training'],
            'mutu'         => ['mutu', 'bank_spo', 'manajemen_risiko'],
            'desaingrafis' => ['desain_grafis'],
        ];

        if (isset($adminAccess[$username]) && in_array($menu, $adminAccess[$username])) {
            return true;
        }

        return in_array($action, ['create', 'read']);
    }
}
