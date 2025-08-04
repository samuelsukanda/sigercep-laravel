<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MandatoryTraining;
use Illuminate\Support\Facades\Storage;

class MandatoryTrainingController extends Controller
{

    public function index(Request $request)
    {
        $mandatoryTraining = MandatoryTraining::all();

        $query = MandatoryTraining::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $mandatoryTraining = $query->latest()->get();

        return view('pages.sdm-hukum.mandatory-training.index', compact('mandatoryTraining'));
    }

    public function create()
    {
        return view('pages.sdm-hukum.mandatory-training.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('nama_file');
        $originalName = $file->getClientOriginalName();
        $folderPath = "mandatory-training";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['nama_file' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        MandatoryTraining::create([
            'nama_file' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.mandatory-training.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);
        return view('pages.sdm-hukum.mandatory-training.detail', compact('mandatoryTraining'));
    }

    public function showFile($id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);

        $filePath = storage_path("app/public/{$mandatoryTraining->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $mandatoryTraining->unit . '-' . $mandatoryTraining->nama_file . '"'
        ]);
    }

    public function edit(string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);
        return view('pages.sdm-hukum.mandatory-training.edit', compact('peraturanPerusahaan'));
    }

    public function update(Request $request, string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);

        $request->validate([
            'nama_file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('nama_file');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $mandatoryTraining->nama_file;

        $targetPath = "mandatory-training/" . $originalName;

        if ($uploadedFile) {
            if (
                $mandatoryTraining->file_path !== $targetPath &&
                Storage::disk('public')->exists($mandatoryTraining->file_path)
            ) {
                Storage::disk('public')->delete($mandatoryTraining->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['nama_file' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("mandatory-training", $uploadedFile, $originalName);
        } else {
            if ($mandatoryTraining->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($mandatoryTraining->file_path)) {
                    return back()->withErrors(['nama_file' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['nama_file' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($mandatoryTraining->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($mandatoryTraining->file_path);
            }
        }

        $mandatoryTraining->update([
            'nama_file' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.mandatory-training.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);

        if (Storage::disk('public')->exists($mandatoryTraining->file_path)) {
            Storage::disk('public')->delete($mandatoryTraining->file_path);
        }

        $mandatoryTraining->delete();

        return redirect()->route('sdm-hukum.mandatory-training.index')->with('success', 'Data berhasil dihapus.');
    }
}
