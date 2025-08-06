<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use Illuminate\Support\Facades\Storage;

class SuratKeputusanController extends Controller
{

    public function index(Request $request)
    {
        $suratKeputusan = SuratKeputusan::all();

        $query = SuratKeputusan::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $suratKeputusan = $query->latest()->get();

        return view('pages.sdm-hukum.surat-keputusan.index', compact('suratKeputusan'));
    }

    public function create()
    {
        return view('pages.sdm-hukum.surat-keputusan.create');
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
        $folderPath = "surat-keputusan/$unit";
        $targetPath = "$folderPath/$originalName";

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['nama_file' => 'File sudah ada di unit ini.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        SuratKeputusan::create([
            'unit' => $unit,
            'nama_file' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.surat-keputusan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);
        return view('pages.sdm-hukum.surat-keputusan.detail', compact('suratKeputusan'));
    }

    public function showFile($id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);

        $filePath = storage_path("app/public/{$suratKeputusan->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $suratKeputusan->unit . '-' . $suratKeputusan->nama_file . '"'
        ]);
    }


    public function edit(string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);
        return view('pages.sdm-hukum.surat-keputusan.edit', compact('suratKeputusan'));
    }

    public function update(Request $request, string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'nama_file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $uploadedFile = $request->file('nama_file');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $suratKeputusan->nama_file;

        $targetPath = "surat-keputusan/$unit/" . $originalName;

        if ($uploadedFile) {
            if ($suratKeputusan->file_path !== $targetPath && Storage::disk('public')->exists($suratKeputusan->file_path)) {
                Storage::disk('public')->delete($suratKeputusan->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
            }

            Storage::disk('public')->putFileAs("surat-keputusan/$unit", $uploadedFile, $originalName);
        } else {
            if ($suratKeputusan->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($suratKeputusan->file_path)) {
                    return back()->withErrors(['nama_file' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
                }

                $fileContent = Storage::disk('public')->get($suratKeputusan->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);

                Storage::disk('public')->delete($suratKeputusan->file_path);
            }
        }

        $suratKeputusan->update([
            'nama_file' => $originalName,
            'file_path' => $targetPath,
            'unit' => $unit,
        ]);

        return redirect()->route('sdm-hukum.surat-keputusan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);

        if (Storage::disk('public')->exists($suratKeputusan->file_path)) {
            Storage::disk('public')->delete($suratKeputusan->file_path);
        }

        $suratKeputusan->delete();

        return redirect()->route('sdm-hukum.surat-keputusan.index')->with('success', 'Data berhasil dihapus.');
    }
}
