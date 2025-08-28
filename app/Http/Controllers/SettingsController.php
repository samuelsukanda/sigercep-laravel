<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SettingsController extends Controller
{

    public function index()
    {
        $user = User::all();
        return view('settings.index', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('settings.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|in:SUPERADMIN,ADMIN,USER',
        ]);

        $user->update([
            'name' => $request->name,
            'level' => $request->level,
        ]);

        return redirect()->route('settings.index')->with('success', 'Role berhasil diperbarui.');
    }
}
