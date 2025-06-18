<?php

namespace App\Http\Controllers;

use App\Models\ReservasiRuangan;
use Illuminate\Http\Request;

class ReservasiRuanganController extends Controller
{
    public function index()
    {
        $reservasi = ReservasiRuangan::all();
        return view('pages.reservasi.ruangan.index', compact('reservasi'));
    }

    public function create()
    {
        return view('pages.reservasi.ruangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal' => 'required|date',
            'ruang' => 'required|string|max:50',
            'approval' => 'nullable|in:Pending,Approved,Rejected',
        ], [
            'jam_selesai.after' => 'Jam Selesai harus lebih besar dari Jam Mulai.',
        ]);

        ReservasiRuangan::create($validated);

        return redirect()->route('reservasi.ruangan.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $reservasi = ReservasiRuangan::findOrFail($id);
        return view('pages.reservasi.ruangan.edit', compact('reservasi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal' => 'required|date',
            'ruang' => 'required|string|max:50',
            'approval' => 'nullable|in:Pending,Approved,Rejected',
        ], [
            'jam_selesai.after' => 'Jam Selesai harus lebih besar dari Jam Mulai.',
        ]);

        $reservasi = ReservasiRuangan::findOrFail($id);
        $reservasi->update($validated);

        return redirect()->route('reservasi.ruangan.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = ReservasiRuangan::findOrFail($id);
        $reservasi->delete();

        return redirect()->route('reservasi.ruangan.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
