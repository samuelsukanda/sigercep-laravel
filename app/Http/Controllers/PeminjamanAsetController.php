<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanAset;
use Illuminate\Support\Facades\Storage;

class PeminjamanAsetController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:peminjaman_aset,read')->only(['index', 'show']);
        $this->middleware('permission:peminjaman_aset,create')->only(['create', 'store']);
        $this->middleware('permission:peminjaman_aset,update')->only(['edit', 'update']);
        $this->middleware('permission:peminjaman_aset,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $pengadaan = PeminjamanAset::all();

        $query = PeminjamanAset::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $pengadaan = $query->latest()->get();

        return view('pages.pengadaan-aset.peminjaman-aset.index', compact('pengadaan'));
    }

    public function create()
    {
        return view('pages.pengadaan-aset.peminjaman-aset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'tempat_asal_barang' => 'required|string|max:50',
            'foto_barang' => 'required|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto_barang')) {
            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/peminjaman-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/peminjaman-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        PeminjamanAset::create($validated);

        return redirect()->route('pengadaan-aset.peminjaman-aset.index')->with('success', 'Data berhasil disimpan.');
    }


    public function show(string $id)
    {
        $pengadaan = PeminjamanAset::findOrFail($id);
        return view('pages.pengadaan-aset.peminjaman-aset.detail', compact('pengadaan'));
    }

    public function edit(string $id)
    {
        $pengadaan = PeminjamanAset::findOrFail($id);
        return view('pages.pengadaan-aset.peminjaman-aset.edit', compact('pengadaan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'tempat_asal_barang' => 'required|string|max:50',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $pengadaan = PeminjamanAset::findOrFail($id);

        if ($request->hasFile('foto_barang')) {
            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/peminjaman-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/peminjaman-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        $pengadaan->update($validated);

        return redirect()->route('pengadaan-aset.peminjaman-aset.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengadaan = PeminjamanAset::findOrFail($id);

        if ($pengadaan->foto_barang && Storage::disk('public')->exists($pengadaan->foto_barang)) {
            Storage::disk('public')->delete($pengadaan->foto_barang);
        }

        if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
            Storage::disk('public')->delete($pengadaan->foto_barcode);
        }

        $pengadaan->delete();

        return redirect()->route('pengadaan-aset.peminjaman-aset.index')->with('success', 'Data berhasil dihapus.');
    }
}
