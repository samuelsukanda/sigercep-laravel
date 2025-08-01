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

        $file = $request->file('nama_file');
        $originalName = $file->getClientOriginalName();
        $unit = $request->unit;
        $targetPath = "utw/$unit/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['nama_file' => 'File sudah ada di unit ini.']);
        }

        $file->storeAs("public/utw/$unit", $originalName);

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
        $file = $request->file('nama_file');
        $originalName = $file ? $file->getClientOriginalName() : $utw->nama_file;
        $newPath = "utw/$unit/$originalName";

        if ($file) {
            if ($newPath !== $utw->file_path && Storage::disk('public')->exists($newPath)) {
                return back()->withErrors(['nama_file' => 'File dengan nama ini sudah ada.']);
            }

            if (Storage::disk('public')->exists($utw->file_path)) {
                Storage::disk('public')->delete($utw->file_path);
            }

            $file->storeAs("public/utw/$unit", $originalName);
        } else {
            if ($newPath !== $utw->file_path && Storage::disk('public')->exists($newPath)) {
                return back()->withErrors(['nama_file' => 'File dengan nama ini sudah ada di unit tujuan.']);
            }

            if (Storage::disk('public')->exists($utw->file_path)) {
                $content = Storage::disk('public')->get($utw->file_path);
                Storage::disk('public')->put($newPath, $content);
                Storage::disk('public')->delete($utw->file_path);
            }
        }

        $utw->update([
            'unit' => $unit,
            'nama_file' => $originalName,
            'file_path' => $newPath,
        ]);

        return redirect()->route('komite-mutu.bank-spo.index')->with('success', 'Data berhasil diperbarui.');
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
