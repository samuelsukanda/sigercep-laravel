<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionRule;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('rules')->orderBy('menu')->get();
        return view('layouts.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu' => 'required|string|max:255',
            'action' => 'required|in:read,create,update,delete',
            'rules' => 'nullable|array'
        ]);

        // Cek apakah sudah ada
        $exists = Permission::where('menu', $request->menu)
            ->where('action', $request->action)
            ->exists();

        if ($exists) {
            return redirect()->route('permissions.index')->with('error', 'Permission untuk menu ' . $request->menu . ' dengan action ' . $request->action . ' sudah ada!');
        }

        $permission = Permission::create([
            'menu' => $request->menu,
            'action' => $request->action
        ]);

        if ($request->has('rules')) {
            foreach ($request->rules as $rule) {
                if (!empty($rule['unit']) || !empty($rule['jabatan']) || !empty($rule['name'])) {
                    $permission->rules()->create($rule);
                }
            }
        }

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan!');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'menu' => 'required|string|max:255',
            'action' => 'required|in:read,create,update,delete',
        ]);

        $permission->update([
            'menu' => $request->menu,
            'action' => $request->action
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diupdate!');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus!');
    }

    public function addRule(Request $request, Permission $permission)
    {
        $request->validate([
            'unit' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'name' => 'nullable|string'
        ]);

        $permission->rules()->create($request->only(['unit', 'jabatan', 'name']));

        return redirect()->route('permissions.index')->with('success', 'Rule berhasil ditambahkan!');
    }

    public function deleteRule(PermissionRule $rule)
    {
        $rule->delete();
        return redirect()->route('permissions.index')->with('success', 'Rule berhasil dihapus!');
    }
}