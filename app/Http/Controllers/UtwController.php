<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utw;
use Illuminate\Support\Facades\Storage;


class UtwController extends Controller
{

    public function index(Request $request)
    {
        $utw = Utw::all();

        $query = Utw::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $utw = $query->latest()->get();

        return view('pages.sdm-hukum.utw.index', compact('utw'));
    }

    public function create()
    {
        return view('pages.sdm-hukum.utw.create');
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
        $folderPath = "utw/$unit";
        $targetPath = "$folderPath/$originalName";

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['nama_file' => 'File sudah ada di unit ini.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        Utw::create([
            'unit' => $unit,
            'nama_file' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.utw.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $utw = Utw::findOrFail($id);
        return view('pages.sdm-hukum.utw.detail', compact('utw'));
    }

    public function showFile($id)
    {
        $utw = Utw::findOrFail($id);

        $filePath = storage_path("app/public/{$utw->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $utw->unit . '-' . $utw->nama_file . '"'
        ]);
    }


    public function edit(string $id)
    {
        $utw = Utw::findOrFail($id);
        return view('pages.sdm-hukum.utw.edit', compact('utw'));
    }

    public function update(Request $request, string $id)
    {
        $utw = Utw::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'nama_file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $uploadedFile = $request->file('nama_file');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $utw->nama_file;

        $targetPath = "utw/$unit/" . $originalName;

        if ($uploadedFile) {
            if ($utw->file_path !== $targetPath && Storage::disk('public')->exists($utw->file_path)) {
                Storage::disk('public')->delete($utw->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
            }

            Storage::disk('public')->putFileAs("utw/$unit", $uploadedFile, $originalName);
        } else {
            if ($utw->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($utw->file_path)) {
                    return back()->withErrors(['nama_file' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
                }

                $fileContent = Storage::disk('public')->get($utw->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);

                Storage::disk('public')->delete($utw->file_path);
            }
        }

        $utw->update([
            'nama_file' => $originalName,
            'file_path' => $targetPath,
            'unit' => $unit,
        ]);

        return redirect()->route('sdm-hukum.utw.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $utw = Utw::findOrFail($id);

        if (Storage::disk('public')->exists($utw->file_path)) {
            Storage::disk('public')->delete($utw->file_path);
        }

        $utw->delete();

        return redirect()->route('sdm-hukum.utw.index')->with('success', 'Data berhasil dihapus.');
    }
}
