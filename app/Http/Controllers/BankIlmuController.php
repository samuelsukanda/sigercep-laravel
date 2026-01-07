<?php

namespace App\Http\Controllers;

use App\Models\BankIlmu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BankIlmuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:bank_ilmu,read')->only(['index', 'show']);
        $this->middleware('permission:bank_ilmu,create')->only(['create', 'store']);
        $this->middleware('permission:bank_ilmu,update')->only(['edit', 'update']);
        $this->middleware('permission:bank_ilmu,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $bankIlmu = BankIlmu::all();

        $query = BankIlmu::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $bankIlmu = $query->latest()->get();

        return view('pages.bank-ilmu.index', compact('bankIlmu'));
    }

    public function create()
    {
        return view('pages.bank-ilmu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "bank-ilmu";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        bankIlmu::create([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('bank-ilmu.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $bankIlmu = bankIlmu::findOrFail($id);
        return view('pages.bank-ilmu.detail', compact('bankIlmu'));
    }

    public function showFile($id)
    {
        $bankIlmu = bankIlmu::findOrFail($id);

        $filePath = storage_path("app/public/{$bankIlmu->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $bankIlmu->unit . '-' . $bankIlmu->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $bankIlmu = bankIlmu::findOrFail($id);
        return view('pages.bank-ilmu.edit', compact('bankIlmu'));
    }

    public function update(Request $request, string $id)
    {
        $bankIlmu = bankIlmu::findOrFail($id);

        $request->validate([
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $bankIlmu->file_pdf;

        $targetPath = "bank-ilmu/" . $originalName;

        if ($uploadedFile) {
            if (
                $bankIlmu->file_path !== $targetPath &&
                Storage::disk('public')->exists($bankIlmu->file_path)
            ) {
                Storage::disk('public')->delete($bankIlmu->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("bank-ilmu", $uploadedFile, $originalName);
        } else {
            if ($bankIlmu->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($bankIlmu->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($bankIlmu->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($bankIlmu->file_path);
            }
        }

        $bankIlmu->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('bank-ilmu.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bankIlmu = bankIlmu::findOrFail($id);

        if (Storage::disk('public')->exists($bankIlmu->file_path)) {
            Storage::disk('public')->delete($bankIlmu->file_path);
        }

        $bankIlmu->delete();

        return redirect()->route('bank-ilmu.index')->with('success', 'Data berhasil dihapus.');
    }
}
