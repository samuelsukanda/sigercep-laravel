<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanDokumen;
use Illuminate\Support\Facades\Storage;

class PengajuanDokumenController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pengajuan_dokumen,read')->only(['index', 'show']);
        $this->middleware('permission:pengajuan_dokumen,create')->only(['create', 'store']);
        $this->middleware('permission:pengajuan_dokumen,update')->only(['edit', 'update']);
        $this->middleware('permission:pengajuan_dokumen,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $pengajuanDokumen = PengajuanDokumen::all();

        $query = PengajuanDokumen::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $pengajuanDokumen = $query->latest()->get();

        return view('pages.komite-mutu.pengajuan-dokumen.index', compact('pengajuanDokumen'));
    }

    public function create()
    {
        return view('pages.komite-mutu.pengajuan-dokumen.create');
    }

    public function store(Request $request)
    {
        // VALIDASI
        $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'permintaan_pengajuan' => 'required|string|max:255',
            'kategori_pengajuan' => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:255',
            'judul_dokumen' => 'required|string|max:255',
            'nomor_revisi' => 'required|string|max:255',
            'alasan_pengajuan' => 'required|string|max:255',
            'bagian_yang_direvisi' => 'required|string|max:255',
            'sebelum_revisi' => 'required|string|max:255',
            'usulan_revisi' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'diajukan_oleh' => 'required|string|max:255',
            'diperiksa_oleh' => 'required|string|max:255',
            'disetujui_oleh' => 'required|string|max:255',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        // HANDLE FILE
        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "pengajuan-dokumen";
        $targetPath = "$folderPath/$originalName";

        // CEK FILE DUPLIKAT
        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada.']);
        }

        // SIMPAN FILE
        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        // SIMPAN DATA
        PengajuanDokumen::create([
            'jenis_dokumen' => $request->jenis_dokumen,
            'permintaan_pengajuan' => $request->permintaan_pengajuan,
            'kategori_pengajuan' => $request->kategori_pengajuan,
            'nomor_dokumen' => $request->nomor_dokumen,
            'judul_dokumen' => $request->judul_dokumen,
            'nomor_revisi' => $request->nomor_revisi,
            'alasan_pengajuan' => $request->alasan_pengajuan,
            'bagian_yang_direvisi' => $request->bagian_yang_direvisi,
            'sebelum_revisi' => $request->sebelum_revisi,
            'usulan_revisi' => $request->usulan_revisi,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'diajukan_oleh' => $request->diajukan_oleh,
            'diperiksa_oleh' => $request->diperiksa_oleh,
            'disetujui_oleh' => $request->disetujui_oleh,
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('komite-mutu.pengajuan-dokumen.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);
        return view('pages.komite-mutu.pengajuan-dokumen.show', compact('pengajuanDokumen'));
    }

    public function showFile($id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        $filePath = storage_path("app/public/{$pengajuanDokumen->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $pengajuanDokumen->unit . '-' . $pengajuanDokumen->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);
        return view('pages.komite-mutu.pengajuan-dokumen.edit', compact('pengajuanDokumen'));
    }

    public function update(Request $request, string $id)
    {

        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'permintaan_pengajuan' => 'required|string|max:255',
            'kategori_pengajuan' => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:100',
            'judul_dokumen' => 'required|string|max:255',
            'nomor_revisi' => 'required|string|max:50',
            'alasan_pengajuan' => 'required|string|max:500',
            'bagian_yang_direvisi' => 'required|string|max:500',
            'sebelum_revisi' => 'required|string|max:1000',
            'usulan_revisi' => 'required|string|max:1000',
            'tanggal_pengajuan' => 'required|date',
            'diajukan_oleh' => 'required|string|max:255',
            'diperiksa_oleh' => 'required|string|max:255',
            'disetujui_oleh' => 'required|string|max:255',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $pengajuanDokumen->file_pdf;

        $targetPath = "pengajuan-dokumen/" . $originalName;

        if ($uploadedFile) {
            if (
                $pengajuanDokumen->file_path !== $targetPath &&
                Storage::disk('public')->exists($pengajuanDokumen->file_path)
            ) {
                Storage::disk('public')->delete($pengajuanDokumen->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("pengajuan-dokumen", $uploadedFile, $originalName);
        } else {
            if ($pengajuanDokumen->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($pengajuanDokumen->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($pengajuanDokumen->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($pengajuanDokumen->file_path);
            }
        }

        $pengajuanDokumen->update([
            'jenis_dokumen' => 'required|string|max:255',
            'permintaan_pengajuan' => 'required|string|max:255',
            'kategori_pengajuan' => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:100',
            'judul_dokumen' => 'required|string|max:255',
            'nomor_revisi' => 'required|string|max:50',
            'alasan_pengajuan' => 'required|string|max:500',
            'bagian_yang_direvisi' => 'required|string|max:500',
            'sebelum_revisi' => 'required|string|max:1000',
            'usulan_revisi' => 'required|string|max:1000',
            'tanggal_pengajuan' => 'required|date',
            'diajukan_oleh' => 'required|string|max:255',
            'diperiksa_oleh' => 'required|string|max:255',
            'disetujui_oleh' => 'required|string|max:255',
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('komite-mutu.pengajuan-dokumen.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        if (Storage::disk('public')->exists($pengajuanDokumen->file_path)) {
            Storage::disk('public')->delete($pengajuanDokumen->file_path);
        }

        $pengajuanDokumen->delete();

        return redirect()->route('komite-mutu.pengajuan-dokumen.index')->with('success', 'Data berhasil dihapus.');
    }
}
