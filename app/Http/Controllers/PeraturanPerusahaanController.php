<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeraturanPerusahaan;
use Illuminate\Support\Facades\Storage;

class PeraturanPerusahaanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:peraturan_perusahaan,read')->only(['index', 'show']);
        $this->middleware('permission:peraturan_perusahaan,create')->only(['create', 'store']);
        $this->middleware('permission:peraturan_perusahaan,update')->only(['edit', 'update']);
        $this->middleware('permission:peraturan_perusahaan,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::all();

        $query = PeraturanPerusahaan::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $peraturanPerusahaan = $query->latest()->get();

        return view('pages.sdm-hukum.peraturan-perusahaan.index', compact('peraturanPerusahaan'));
    }

    public function create()
    {
        return view('pages.sdm-hukum.peraturan-perusahaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('nama_file');
        $originalName = $file->getClientOriginalName();
        $folderPath = "peraturan-perusahaan";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['nama_file' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        PeraturanPerusahaan::create([
            'nama_file' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.peraturan-perusahaan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);
        return view('pages.sdm-hukum.peraturan-perusahaan.detail', compact('peraturanPerusahaan'));
    }

    public function showFile($id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);

        $filePath = storage_path("app/public/{$peraturanPerusahaan->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $peraturanPerusahaan->unit . '-' . $peraturanPerusahaan->nama_file . '"'
        ]);
    }

    public function edit(string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);
        return view('pages.sdm-hukum.peraturan-perusahaan.edit', compact('peraturanPerusahaan'));
    }

    public function update(Request $request, string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);

        $request->validate([
            'nama_file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('nama_file');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $peraturanPerusahaan->nama_file;

        $targetPath = "peraturan-perusahaan/" . $originalName;

        if ($uploadedFile) {
            if (
                $peraturanPerusahaan->file_path !== $targetPath &&
                Storage::disk('public')->exists($peraturanPerusahaan->file_path)
            ) {
                Storage::disk('public')->delete($peraturanPerusahaan->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['nama_file' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("peraturan-perusahaan", $uploadedFile, $originalName);
        } else {
            if ($peraturanPerusahaan->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($peraturanPerusahaan->file_path)) {
                    return back()->withErrors(['nama_file' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['nama_file' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($peraturanPerusahaan->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($peraturanPerusahaan->file_path);
            }
        }

        $peraturanPerusahaan->update([
            'nama_file' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.peraturan-perusahaan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);

        if (Storage::disk('public')->exists($peraturanPerusahaan->file_path)) {
            Storage::disk('public')->delete($peraturanPerusahaan->file_path);
        }

        $peraturanPerusahaan->delete();

        return redirect()->route('sdm-hukum.peraturan-perusahaan.index')->with('success', 'Data berhasil dihapus.');
    }
}
