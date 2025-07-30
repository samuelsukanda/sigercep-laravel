<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Storage;

class PeminjamanController extends Controller
{

    public function index(Request $request)
    {
        $peminjaman = Peminjaman::all();

        $query = Peminjaman::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $peminjaman = $query->latest()->get();

        return view('pages.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        return view('pages.peminjaman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'barang' => 'required|string|max:50',
            'tanda_tangan' => 'required|string',
            'status' => 'nullable|in:Sudah Di Kembalikan,Belum Di Kembalikan',
        ]);

        if ($request->has('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/peminjaman/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            $validated['tanda_tangan'] = 'storage/' . $path;
        }

        Peminjaman::create($validated);

        return redirect()->route('peminjaman.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.peminjaman.detail', compact('peminjaman'));
    }

    public function edit(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'barang' => 'required|string|max:50',
            'tanda_tangan' => 'nullable|string',
            'status' => 'nullable|in:Sudah Di Kembalikan,Belum Di Kembalikan',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($request->filled('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/peminjaman/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            if ($peminjaman->tanda_tangan && Storage::disk('public')->exists(str_replace('storage/', '', $peminjaman->tanda_tangan))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $peminjaman->tanda_tangan));
            }

            $validated['tanda_tangan'] = 'storage/' . $path;
        } else {
            $validated['tanda_tangan'] = $peminjaman->tanda_tangan;
        }

        $peminjaman->update($validated);

        return redirect()->route('peminjaman.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->tanda_tangan) {
            $signaturePath = str_replace('storage/', '', $peminjaman->tanda_tangan);
            if (Storage::disk('public')->exists($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Data berhasil dihapus.');
    }
}
