<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KecelakaanKerja;
use Illuminate\Support\Facades\Storage;

class KecelakaanKerjaController extends Controller
{

    public function index()
    {
        $k3rs = KecelakaanKerja::all();
        return view('pages.kecelakaan-kerja.index', compact('k3rs'));
    }

    public function create()
    {
        return view('pages.kecelakaan-kerja.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'no_hp' => 'required|string|max:20',
            'jam' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'jenis_kecelakaan' => 'required|string|max:50',
            'lokasi_kecelakaan' => 'required|string|max:550',
            'saksi' => 'required|string|max:50',
            'kegiatan' => 'required|string',
            'riwayat' => 'required|string',
            'penyebab' => 'required|string',
            'bahan' => 'required|string|max:50',
            'cedera' => 'required|string|max:50',
            'pengobatan' => 'required|string|max:50',
            'pengobatan2' => 'required|string|max:50',
            'pelaksana' => 'required|string|max:50',
            'tanda_tangan' => 'required|string',
        ]);

        if ($request->has('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/kecelakaan-kerja/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            $validated['tanda_tangan'] = 'storage/' . $path;
        }

        KecelakaanKerja::create($validated);

        return redirect()->route('kecelakaan-kerja.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $k3rs = KecelakaanKerja::findOrFail($id);
        return view('pages.kecelakaan-kerja.detail', compact('k3rs'));
    }

    public function edit(string $id)
    {
        $k3rs = KecelakaanKerja::findOrFail($id);
        return view('pages.kecelakaan-kerja.edit', compact('k3rs'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'no_hp' => 'required|string|max:20',
            'jam' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'jenis_kecelakaan' => 'required|string|max:50',
            'lokasi_kecelakaan' => 'required|string|max:550',
            'saksi' => 'required|string|max:50',
            'kegiatan' => 'required|string',
            'riwayat' => 'required|string',
            'penyebab' => 'required|string',
            'bahan' => 'required|string|max:50',
            'cedera' => 'required|string|max:50',
            'pengobatan' => 'required|string|max:50',
            'pengobatan2' => 'required|string|max:50',
            'pelaksana' => 'required|string|max:50',
            'tanda_tangan' => 'nullable|string',
        ]);

        $k3rs = KecelakaanKerja::findOrFail($id);

        if ($request->filled('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/kecelakaan-kerja/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            if ($k3rs->tanda_tangan && Storage::disk('public')->exists(str_replace('storage/', '', $k3rs->tanda_tangan))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $k3rs->tanda_tangan));
            }

            $validated['tanda_tangan'] = 'storage/' . $path;
        } else {
            $validated['tanda_tangan'] = $k3rs->tanda_tangan;
        }

        $k3rs->update($validated);

        return redirect()->route('kecelakaan-kerja.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $k3rs = KecelakaanKerja::findOrFail($id);

        if ($k3rs->tanda_tangan) {
            $signaturePath = str_replace('storage/', '', $k3rs->tanda_tangan);
            if (Storage::disk('public')->exists($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }
        }

        $k3rs->delete();

        return redirect()->route('kecelakaan-kerja.index')->with('success', 'Data berhasil dihapus.');
    }
}
