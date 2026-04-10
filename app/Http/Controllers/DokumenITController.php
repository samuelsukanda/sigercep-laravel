<?php

namespace App\Http\Controllers;

use App\Models\DokumenIt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DokumenITController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:dokumen_it,read')->only(['index', 'show']);
        $this->middleware('permission:dokumen_it,create')->only(['create', 'store']);
        $this->middleware('permission:dokumen_it,update')->only(['edit', 'update']);
        $this->middleware('permission:dokumen_it,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
            'jenis_dokumen'

        ]);

        $validator = Validator::make($request->all(), [
            'periode_dari'   => 'nullable|date_format:d-m-Y',
            'periode_sampai' => 'nullable|date_format:d-m-Y|after_or_equal:periode_dari',
        ], [
            'periode_sampai.after_or_equal' => 'Periode Sampai harus lebih besar atau sama dengan Periode Dari.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dokumen-it.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = DokumenIt::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($request->filled('jenis_dokumen')) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }

        $DokumenIt = $query->orderBy('created_at', 'desc')->get();

        return view('pages.dokumen-it.index', compact('DokumenIt', 'isFiltered'));
    }

    public function create()
    {
        return view('pages.dokumen-it.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "dokumen-it";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        DokumenIt::create([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
            'jenis_dokumen' => $request->jenis_dokumen,
        ]);

        return redirect()->route('dokumen-it.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);
        return view('pages.dokumen-it.detail', compact('DokumenIt'));
    }

    public function showFile($id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);

        $filePath = storage_path("app/public/{$DokumenIt->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $DokumenIt->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);
        return view('pages.dokumen-it.edit', compact('DokumenIt'));
    }

    public function update(Request $request, string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);

        $request->validate([
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $DokumenIt->file_pdf;

        $targetPath = "dokumen-it/" . $originalName;

        if ($uploadedFile) {
            if (
                $DokumenIt->file_path !== $targetPath &&
                Storage::disk('public')->exists($DokumenIt->file_path)
            ) {
                Storage::disk('public')->delete($DokumenIt->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("dokumen-it", $uploadedFile, $originalName);
        } else {
            if ($DokumenIt->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($DokumenIt->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($DokumenIt->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($DokumenIt->file_path);
            }
        }

        $DokumenIt->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
            'jenis_dokumen' => $request->jenis_dokumen,
        ]);

        return redirect()->route('dokumen-it.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);

        if (Storage::disk('public')->exists($DokumenIt->file_path)) {
            Storage::disk('public')->delete($DokumenIt->file_path);
        }

        $DokumenIt->delete();

        return redirect()->route('dokumen-it.index')->with('success', 'Data berhasil dihapus.');
    }
}
