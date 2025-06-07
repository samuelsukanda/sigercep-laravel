<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomplainIpsrs;

class KomplainIpsrsController extends Controller
{
    // Menampilkan daftar komplain IPSRS
    public function index()
    {
        $komplain = KomplainIpsrs::all();
        return view('pages.komplain.ipsrs.index', compact('komplain'));
    }

    // Menampilkan form tambah komplain
    public function create()
    {
        return view('pages.komplain.ipsrs.create');
    }

    // Menyimpan komplain baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('komplain_ipsrs', 'public');
        }

        KomplainIpsrs::create($data);

        return redirect()->route('komplain.ipsrs.index')->with('success', 'Data berhasil disimpan.');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);
        return view('pages.komplain.ipsrs.edit', compact('komplain'));
    }

    // Menyimpan perubahan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $komplain = KomplainIpsrs::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('komplain_ipsrs', 'public');
        }

        $komplain->update($data);

        return redirect()->route('komplain.ipsrs.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Menghapus komplain
    public function destroy($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);
        $komplain->delete();

        return redirect()->route('komplain.ipsrs.index')->with('success', 'Data berhasil dihapus.');
    }
}
