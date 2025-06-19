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

        // Check for overlapping reservations
        $isOverlap = ReservasiRuangan::where('ruang', $validated['ruang'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                            ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

        ReservasiRuangan::create($validated);

        return redirect()->route('reservasi.ruangan.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $reservasi = ReservasiRuangan::findOrFail($id);
        return view('pages.reservasi.ruangan.edit', compact('reservasi'));
    }

    public function show($id)
    {
        $reservasi = ReservasiRuangan::findOrFail($id);
        return view('pages.reservasi.ruangan.detail', compact('reservasi'));
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

        // Check for overlapping reservations
        $isOverlap = ReservasiRuangan::where('id', '<>', $id)
            ->where('ruang', $validated['ruang'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                            ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

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
