<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanPerilaku;
use Illuminate\Support\Facades\Storage;

class LaporanPerilakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan_perilaku,read')->only(['index', 'show']);
        $this->middleware('permission:laporan_perilaku,create')->only(['create', 'store']);
        $this->middleware('permission:laporan_perilaku,update')->only(['edit', 'update']);
        $this->middleware('permission:laporan_perilaku,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $laporanPerilaku = LaporanPerilaku::all();

        $query = LaporanPerilaku::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $laporanPerilaku = $query->latest()->get();

        return view('pages.komite-mutu.laporan-perilaku.index', compact('laporanPerilaku'));
    }

    public function create()
    {
        return view('pages.komite-mutu.laporan-perilaku.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kategori_laporan' => 'required|string|max:255',
            'keterangan_perilaku' => 'required|string|max:255',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();

        $validated['file_pdf'] = $originalName;
        $validated['file_path'] = 'laporan-perilaku/' . $originalName;

        if (Storage::disk('public')->exists($validated['file_path'])) {
            return back()->withErrors([
                'file_pdf' => 'File dengan nama ini sudah ada.'
            ]);
        }

        Storage::disk('public')->putFileAs(
            'laporan-perilaku',
            $file,
            $originalName
        );

        LaporanPerilaku::create($validated);

        return redirect()
            ->route('komite-mutu.laporan-perilaku.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $laporanPerilaku = LaporanPerilaku::findOrFail($id);
        return view('pages.komite-mutu.laporan-perilaku.detail', compact('laporanPerilaku'));
    }

    public function showFile($id)
    {
        $laporanPerilaku = LaporanPerilaku::findOrFail($id);

        $filePath = storage_path("app/public/{$laporanPerilaku->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $laporanPerilaku->unit . '-' . $laporanPerilaku->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $laporanPerilaku = laporanPerilaku::findOrFail($id);
        return view('pages.komite-mutu.laporan-perilaku.edit', compact('laporanPerilaku'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kategori_laporan' => 'required|string|max:255',
            'keterangan_perilaku' => 'required|string|max:255',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $laporanPerilaku = LaporanPerilaku::findOrFail($id);

        if ($request->hasFile('file_pdf')) {
            if (
                $laporanPerilaku->file_path &&
                Storage::disk('public')->exists($laporanPerilaku->file_path)
            ) {
                Storage::disk('public')->delete($laporanPerilaku->file_path);
            }

            $file = $request->file('file_pdf');
            $originalName = $file->getClientOriginalName();
            $validated['file_pdf'] = $originalName;
            $validated['file_path'] = 'laporan-perilaku/' . $originalName;

            Storage::disk('public')->putFileAs(
                'laporan-perilaku',
                $file,
                $originalName
            );
        }

        $laporanPerilaku->update($validated);

        return redirect()
            ->route('komite-mutu.laporan-perilaku.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $laporanPerilaku = LaporanPerilaku::findOrFail($id);

        if (Storage::disk('public')->exists($laporanPerilaku->file_path)) {
            Storage::disk('public')->delete($laporanPerilaku->file_path);
        }

        $laporanPerilaku->delete();

        return redirect()->route('komite-mutu.laporan-perilaku.index')->with('success', 'Data berhasil dihapus.');
    }
}
