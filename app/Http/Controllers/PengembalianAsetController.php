<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengembalianAset;
use Illuminate\Support\Facades\Storage;

class PengembalianAsetController extends Controller
{

    public function index()
    {
        $pengadaan = PengembalianAset::all();
        return view('pages.pengadaan-aset.pengembalian-aset.index', compact('pengadaan'));
    }

    public function create()
    {
        return view('pages.pengadaan-aset.pengembalian-aset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'tempat_asal_barang' => 'required|string|max:50',
            'foto_barang' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_barang')) {
            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/pengembalian-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/pengembalian-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        PengembalianAset::create($validated);

        return redirect()->route('pengadaan-aset.pengembalian-aset.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengadaan = PengembalianAset::findOrFail($id);
        return view('pages.pengadaan-aset.pengembalian-aset.detail', compact('pengadaan'));
    }

    public function edit(string $id)
    {
        $pengadaan = PengembalianAset::findOrFail($id);
        return view('pages.pengadaan-aset.pengembalian-aset.edit', compact('pengadaan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'tempat_asal_barang' => 'required|string|max:50',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pengadaan = PengembalianAset::findOrFail($id);

        if ($request->hasFile('foto_barang')) {
            if ($pengadaan->foto_barang) {
                Storage::disk('public')->delete($pengadaan->foto_barang);
            }
            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/pengembalian-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            if ($pengadaan->foto_barcode) {
                Storage::disk('public')->delete($pengadaan->foto_barcode);
            }
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/pengembalian-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        $pengadaan->update($validated);

        return redirect()->route('pengadaan-aset.pengembalian-aset.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengadaan = PengembalianAset::findOrFail($id);

        if ($pengadaan->foto_barang && Storage::disk('public')->exists($pengadaan->foto_barang)) {
            Storage::disk('public')->delete($pengadaan->foto_barang);
        }

        if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
            Storage::disk('public')->delete($pengadaan->foto_barcode);
        }

        $pengadaan->delete();

        return redirect()->route('pengadaan-aset.pengembalian-aset.index')->with('success', 'Data berhasil dihapus.');
    }
}
