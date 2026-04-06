<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'menu' => 'required',
            'action' => 'required',
            'rules' => 'nullable|array'
        ]);

        // 1. Buat Header Permission
        $permission = Permission::create([
            'menu' => $request->menu,
            'action' => $request->action,
        ]);

        // 2. Simpan Multi-Rules
        if ($request->has('rules')) {
            foreach ($request->rules as $ruleData) {
                // Hanya simpan jika minimal salah satu field terisi
                if (array_filter($ruleData)) {
                    $permission->rules()->create($ruleData);
                }
            }
        }

        return back()->with('success', 'Permission berhasil ditambahkan!');
    }
}
