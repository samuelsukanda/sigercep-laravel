<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomiteMedik;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class KomiteMedikController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:komite_medik,read')->only(['index', 'show']);
        $this->middleware('permission:komite_medik,create')->only(['create', 'store']);
        $this->middleware('permission:komite_medik,update')->only(['edit', 'update']);
        $this->middleware('permission:komite_medik,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
            'unit'
        ]);

        $validator = Validator::make($request->all(), [
            'periode_dari'   => 'nullable|date_format:d-m-Y',
            'periode_sampai' => 'nullable|date_format:d-m-Y|after_or_equal:periode_dari',
        ], [
            'periode_sampai.after_or_equal' => 'Periode Sampai harus lebih besar atau sama dengan Periode Dari.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('komite-medik.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = KomiteMedik::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        $komiteMedik = $query->orderBy('created_at', 'desc')->get();

        return view('pages.komite-medik.index', compact('komiteMedik', 'isFiltered'));
    }

    public function create()
    {
        return view('pages.komite-medik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "komite-medik";
        $targetPath = "$folderPath/$originalName";

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada di unit ini.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        KomiteMedik::create([
            'unit' => $unit,
            'file_pdf' => $originalName,
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
            'Content-Disposition' => 'inline; filename="' . $komiteMedik->unit . '-' . $komiteMedik->file_pdf . '"'
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
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $komiteMedik->file_pdf;

        $targetPath = "komite-medik/" . $originalName;

        if ($uploadedFile) {
            if ($komiteMedik->file_path !== $targetPath && Storage::disk('public')->exists($komiteMedik->file_path)) {
                Storage::disk('public')->delete($komiteMedik->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File sudah ada untuk unit ini.']);
            }

            Storage::disk('public')->putFileAs("komite-medik/", $uploadedFile, $originalName);
        } else {
            if ($komiteMedik->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($komiteMedik->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada untuk unit ini.']);
                }

                $fileContent = Storage::disk('public')->get($komiteMedik->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);

                Storage::disk('public')->delete($komiteMedik->file_path);
            }
        }

        $komiteMedik->update([
            'file_pdf' => $originalName,
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
