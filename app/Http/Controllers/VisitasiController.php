<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class VisitasiController extends Controller
{

    public function index()
    {
        $visitasi = Visitasi::all();
        return view('pages.visitasi.index', compact('visitasi'));
    }

    public function create()
    {
        return view('pages.visitasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'tim' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'visitasi-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/visitasi', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        Visitasi::create($validated);

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);
        return view('pages.visitasi.detail', compact('visitasi'));
    }

    public function edit(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);
        return view('pages.visitasi.edit', compact('visitasi'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'tim' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $visitasi = Visitasi::findOrFail($id);

        if ($request->hasFile('foto')) {
            // Hapus foto lama kalau ada
            if ($visitasi->foto && Storage::disk('public')->exists($visitasi->foto)) {
                Storage::disk('public')->delete($visitasi->foto);
            }

            $file = $request->file('foto');
            $namaFile = 'visitasi-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/visitasi', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        $visitasi->update($validated);

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);

        // Hapus foto jika ada
        if ($visitasi->foto && Storage::disk('public')->exists($visitasi->foto)) {
            Storage::disk('public')->delete($visitasi->foto);
        }

        $visitasi->delete();

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
