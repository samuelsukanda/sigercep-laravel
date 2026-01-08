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
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'permintaan_pengajuan' => 'required|string|max:255',
            'kategori_pengajuan' => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:255',
            'judul_dokumen' => 'required|string|max:255',
            'nomor_revisi' => 'required|string|max:255',
            'alasan_pengajuan' => 'required|string|max:255',
            'bagian_yang_direvisi' => 'nullable|string|max:255',
            'sebelum_revisi' => 'nullable|string|max:255',
            'usulan_revisi' => 'nullable|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'diajukan_oleh' => 'required|string|max:255',
            'diperiksa_oleh' => 'required|string|max:255',
            'disetujui_oleh' => 'required|string|max:255',
            'file_pdf' => 'required|file|mimes:pdf,doc,docx|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();

        $validated['file_pdf'] = $originalName;
        $validated['file_path'] = 'pengajuan-dokumen/' . $originalName;

        if (Storage::disk('public')->exists($validated['file_path'])) {
            return back()->withErrors([
                'file_pdf' => 'File dengan nama ini sudah ada.'
            ]);
        }

        Storage::disk('public')->putFileAs(
            'pengajuan-dokumen',
            $file,
            $originalName
        );

        PengajuanDokumen::create($validated);

        return redirect()
            ->route('komite-mutu.pengajuan-dokumen.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);
        return view('pages.komite-mutu.pengajuan-dokumen.detail', compact('pengajuanDokumen'));
    }

    public function showFile($id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        $filePath = storage_path("app/public/{$pengajuanDokumen->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $filename = trim(
            ($pengajuanDokumen->unit ? $pengajuanDokumen->unit . ' - ' : '') .
                $pengajuanDokumen->file_pdf
        );

        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function edit(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);
        return view('pages.komite-mutu.pengajuan-dokumen.edit', compact('pengajuanDokumen'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
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
            'file_pdf' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
        ]);

        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        if ($request->hasFile('file_pdf')) {
            if (
                $pengajuanDokumen->file_path &&
                Storage::disk('public')->exists($pengajuanDokumen->file_path)
            ) {
                Storage::disk('public')->delete($pengajuanDokumen->file_path);
            }

            $file = $request->file('file_pdf');
            $originalName = $file->getClientOriginalName();
            $validated['file_pdf'] = $originalName;
            $validated['file_path'] = 'pengajuan-dokumen/' . $originalName;

            Storage::disk('public')->putFileAs(
                'pengajuan-dokumen',
                $file,
                $originalName
            );
        }

        $pengajuanDokumen->update($validated);

        return redirect()
            ->route('komite-mutu.pengajuan-dokumen.index')
            ->with('success', 'Data berhasil diperbarui.');
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
