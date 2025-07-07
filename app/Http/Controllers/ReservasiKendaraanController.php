<?php

namespace App\Http\Controllers;

use App\Models\ReservasiKendaraan;
use Illuminate\Http\Request;

class ReservasiKendaraanController extends Controller
{

    public function index()
    {
        $reservasi = ReservasiKendaraan::all();
        return view('pages.reservasi.kendaraan.index', compact('reservasi'));
    }

    public function create()
    {
        return view('pages.reservasi.kendaraan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tempat_tujuan' => 'required|string|max:100',
            'keperluan' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_berangkat',
            'tanggal' => 'required|date',
            'jenis_kendaraan' => 'required|string|max:50',
            'jumlah_penumpang' => 'required|string|max:50',
            'waktu_tempuh' => 'required|string|max:50',
            'jarak_tempuh' => 'required|string|max:50',
            'jenis_layanan' => 'required|string|max:50',
        ], [
            'jam_pulang.after' => 'Jam Pulang harus lebih besar dari Jam Berangkat.',
        ]);

        $isOverlap = ReservasiKendaraan::where('jenis_kendaraan', $validated['jenis_kendaraan'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_berangkat', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_berangkat', '<=', $validated['jam_berangkat'])
                            ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

        ReservasiKendaraan::create($validated);

        return redirect()->route('reservasi.kendaraan.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $reservasi = ReservasiKendaraan::findOrFail($id);
        return view('pages.reservasi.kendaraan.edit', compact('reservasi'));
    }

    public function show(string $id)
    {
        $reservasi = ReservasiKendaraan::findOrFail($id);
        return view('pages.reservasi.kendaraan.detail', compact('reservasi'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tempat_tujuan' => 'required|string|max:100',
            'keperluan' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_berangkat',
            'tanggal' => 'required|date',
            'jenis_kendaraan' => 'required|string|max:50',
            'jumlah_penumpang' => 'required|string|max:50',
            'waktu_tempuh' => 'required|string|max:50',
            'jarak_tempuh' => 'required|string|max:50',
            'jenis_layanan' => 'required|string|max:50',
        ], [
            'jam_pulang.after' => 'Jam Pulang harus lebih besar dari Jam Berangkat.',
        ]);

        $isOverlap = ReservasiKendaraan::where('id', '<>', $id)
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_berangkat', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_berangkat', '<=', $validated['jam_berangkat'])
                            ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

        $reservasi = ReservasiKendaraan::findOrFail($id);
        $reservasi->update($validated);

        return redirect()->route('reservasi.kendaraan.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $reservasi = ReservasiKendaraan::findOrFail($id);
        $reservasi->delete();

        return redirect()->route('reservasi.kendaraan.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
