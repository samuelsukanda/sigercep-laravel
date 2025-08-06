<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomiteMedik;
use Illuminate\Support\Facades\Storage;

class KomiteMedikController extends Controller
{
    public function index(Request $request)
    {
        $komiteMedik = KomiteMedik::all();

        $query = KomiteMedik::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $komiteMedik = $query->latest()->get();

        return view('pages.komite-medik.index', compact('komiteMedik'));
    }

    public function create()
    {
        return view('pages.komite-medik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'nama_file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $file = $request->file('nama_file');
        $originalName = $file->getClientOriginalName();
        $folderPath = "komite-medik/$unit";
        $targetPath = "$folderPath/$originalName";

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['nama_file' => 'File sudah ada di unit ini.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        KomiteMedik::create([
            'unit' => $unit,
            'nama_file' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('komite-medik.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);
        return view('pages.komite-medik.detail', compact('komiteMedik'));
    }

    public function showFile($id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);

        $filePath = storage_path("app/public/{$komiteMedik->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $komiteMedik->unit . '-' . $komiteMedik->nama_file . '"'
        ]);
    }


    public function edit(string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);
        return view('pages.komite-medik.edit', compact('komiteMedik'));
    }

    public function update(Request $request, string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'nama_file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $uploadedFile = $request->file('nama_file');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $komiteMedik->nama_file;

        $targetPath = "komite-medik/$unit/" . $originalName;

        if ($uploadedFile) {
            if ($komiteMedik->file_path !== $targetPath && Storage::disk('public')->exists($komiteMedik->file_path)) {
                Storage::disk('public')->delete($komiteMedik->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
            }

            Storage::disk('public')->putFileAs("komite-medik/$unit", $uploadedFile, $originalName);
        } else {
            if ($komiteMedik->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($komiteMedik->file_path)) {
                    return back()->withErrors(['nama_file' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
                }

                $fileContent = Storage::disk('public')->get($komiteMedik->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);

                Storage::disk('public')->delete($komiteMedik->file_path);
            }
        }

        $komiteMedik->update([
            'nama_file' => $originalName,
            'file_path' => $targetPath,
            'unit' => $unit,
        ]);

        return redirect()->route('komite-medik.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);

        if (Storage::disk('public')->exists($komiteMedik->file_path)) {
            Storage::disk('public')->delete($komiteMedik->file_path);
        }

        $komiteMedik->delete();

        return redirect()->route('komite-medik.index')->with('success', 'Data berhasil dihapus.');
    }
}