<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemindahanAset;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PemindahanAsetController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pemindahan_aset,read')->only(['index', 'show']);
        $this->middleware('permission:pemindahan_aset,create')->only(['create', 'store']);
        $this->middleware('permission:pemindahan_aset,update')->only(['edit', 'update']);
        $this->middleware('permission:pemindahan_aset,delete')->only(['destroy']);
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
            return redirect()->route('pengadaan-aset.pemindahan-aset.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = PemindahanAset::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $pengadaan = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.pengadaan-aset.pemindahan-aset.index', compact('pengadaan', 'isFiltered'));
    }

    public function create()
    {
        return view('pages.pengadaan-aset.pemindahan-aset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit_asal' => 'required|string|max:50',
            'unit_tujuan' => 'required|string|max:50',
            'keperluan' => 'required|string',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'foto_barang' => 'required|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto_barang')) {
            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/pemindahan-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/pemindahan-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        PemindahanAset::create($validated);

        return redirect()->route('pengadaan-aset.pemindahan-aset.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengadaan = PemindahanAset::findOrFail($id);
        return view('pages.pengadaan-aset.pemindahan-aset.detail', compact('pengadaan'));
    }

    public function edit(string $id)
    {
        $pengadaan = PemindahanAset::findOrFail($id);
        return view('pages.pengadaan-aset.pemindahan-aset.edit', compact('pengadaan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit_asal' => 'required|string|max:50',
            'unit_tujuan' => 'required|string|max:50',
            'keperluan' => 'required|string',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $pengadaan = PemindahanAset::findOrFail($id);

        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto_barang && Storage::disk('public')->exists($pengadaan->foto_barang)) {
                Storage::disk('public')->delete($pengadaan->foto_barang);
            }

            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/pemindahan-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
                Storage::disk('public')->delete($pengadaan->foto_barcode);
            }

            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/pemindahan-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        $pengadaan->update($validated);

        return redirect()->route('pengadaan-aset.pemindahan-aset.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengadaan = PemindahanAset::findOrFail($id);

        if ($pengadaan->foto_barang && Storage::disk('public')->exists($pengadaan->foto_barang)) {
            Storage::disk('public')->delete($pengadaan->foto_barang);
        }

        if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
            Storage::disk('public')->delete($pengadaan->foto_barcode);
        }

        $pengadaan->delete();

        return redirect()->route('pengadaan-aset.pemindahan-aset.index')->with('success', 'Data berhasil dihapus.');
    }
}
