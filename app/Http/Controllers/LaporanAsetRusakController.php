<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanAsetRusak;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LaporanAsetRusakController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:laporan_aset_rusak,read')->only(['index', 'show']);
        $this->middleware('permission:laporan_aset_rusak,create')->only(['create', 'store']);
        $this->middleware('permission:laporan_aset_rusak,update')->only(['edit', 'update']);
        $this->middleware('permission:laporan_aset_rusak,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
        ]);

        $validator = Validator::make($request->all(), [
            'periode_dari'   => 'nullable|date_format:d-m-Y',
            'periode_sampai' => 'nullable|date_format:d-m-Y|after_or_equal:periode_dari',
        ], [
            'periode_sampai.after_or_equal' => 'Periode Sampai harus lebih besar atau sama dengan Periode Dari.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pengadaan-aset.laporan-aset-rusak.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = LaporanAsetRusak::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $pengadaan = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.pengadaan-aset.laporan-aset-rusak.index', compact('pengadaan', 'isFiltered'));
    }

    public function create()
    {
        return view('pages.pengadaan-aset.laporan-aset-rusak.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'nama_aset' => 'required|string|max:50',
            'lokasi_aset' => 'required|string|max:50',
            'kondisi_aset' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'status' => 'nullable|in:Rusak Total,Bisa Diperbaiki',
            'foto' => 'required|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFileFoto = 'foto-' . now()->format('YmdHis') . '.' . $foto->getClientOriginalExtension();
            $pathFoto = $foto->storeAs('images/laporan-aset-rusak/foto-barang', $namaFileFoto, 'public');
            $validated['foto'] = $pathFoto;
        }

        if ($request->hasFile('foto_barcode')) {
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/laporan-aset-rusak/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        LaporanAsetRusak::create($validated);

        return redirect()->route('pengadaan-aset.laporan-aset-rusak.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengadaan = LaporanAsetRusak::findOrFail($id);
        return view('pages.pengadaan-aset.laporan-aset-rusak.detail', compact('pengadaan'));
    }

    public function edit(string $id)
    {
        $pengadaan = LaporanAsetRusak::findOrFail($id);
        return view('pages.pengadaan-aset.laporan-aset-rusak.edit', compact('pengadaan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'nama_aset' => 'required|string|max:50',
            'lokasi_aset' => 'required|string|max:50',
            'kondisi_aset' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'status' => 'nullable|in:Rusak Total,Bisa Diperbaiki',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $pengadaan = LaporanAsetRusak::findOrFail($id);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto && Storage::disk('public')->exists($pengadaan->foto)) {
                Storage::disk('public')->delete($pengadaan->foto);
            }

            $foto = $request->file('foto');
            $namaFileFoto = 'foto-' . now()->format('YmdHis') . '.' . $foto->getClientOriginalExtension();
            $pathFoto = $foto->storeAs('images/laporan-aset-rusak/foto-barang', $namaFileFoto, 'public');
            $validated['foto'] = $pathFoto;
        }

        if ($request->hasFile('foto_barcode')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
                Storage::disk('public')->delete($pengadaan->foto_barcode);
            }

            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/laporan-aset-rusak/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        $pengadaan->update($validated);

        return redirect()->route('pengadaan-aset.laporan-aset-rusak.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengadaan = LaporanAsetRusak::findOrFail($id);

        if ($pengadaan->foto && Storage::disk('public')->exists($pengadaan->foto)) {
            Storage::disk('public')->delete($pengadaan->foto);
        }

        if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
            Storage::disk('public')->delete($pengadaan->foto_barcode);
        }

        $pengadaan->delete();

        return redirect()->route('pengadaan-aset.laporan-aset-rusak.index')->with('success', 'Data berhasil dihapus.');
    }
}
