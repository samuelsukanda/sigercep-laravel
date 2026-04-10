<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

class PermissionHelper
{
    public static function canAccess($menu, $action)
    {
        $user = Auth::user();
        if (!$user) return false;

        $name = strtolower(trim($user->name ?? ''));
        $unit = strtolower(trim($user->unit ?? ''));
        $jabatan = strtolower(trim($user->jabatan ?? ''));

        // SUPERADMIN (WILDCARD)
        $hasWildcard = Permission::where('menu', '*')
            ->where('action', '*')
            ->whereHas('rules', function ($q) use ($unit, $jabatan, $name) {
                $q->where(function ($q2) use ($unit) {
                    $q2->whereNull('unit')
                        ->orWhereRaw('LOWER(unit) = ?', [$unit]);
                })->where(function ($q2) use ($jabatan) {
                    $q2->whereNull('jabatan')
                        ->orWhereRaw('LOWER(jabatan) = ?', [$jabatan]);
                })->where(function ($q2) use ($name) {
                    $q2->whereNull('name')
                        ->orWhereRaw('LOWER(name) = ?', [$name]);
                });
            })
            ->exists();

        if ($hasWildcard) return true;

        // CEK EXACT PERMISSION
        $permission = Permission::where('menu', $menu)
            ->where('action', $action)
            ->first();

        if (!$permission) return false;

        return $permission->rules()
            ->where(function ($q) use ($unit, $jabatan, $name) {
                $q->where(function ($q2) use ($unit) {
                    $q2->whereNull('unit')
                        ->orWhereRaw('LOWER(unit) = ?', [$unit]);
                })->where(function ($q2) use ($jabatan) {
                    $q2->whereNull('jabatan')
                        ->orWhereRaw('LOWER(jabatan) = ?', [$jabatan]);
                })->where(function ($q2) use ($name) {
                    $q2->whereNull('name')
                        ->orWhereRaw('LOWER(name) = ?', [$name]);
                });
            })
            ->exists();
    }
}
