<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankSpo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class BankSpoController extends Controller
{

    public function index(Request $request)
    {
        $bank_spo = BankSpo::all();

        $query = BankSpo::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $bank_spo = $query->latest()->get();

        return view('pages.komite-mutu.bank-spo.index', compact('bank_spo'));
    }

    public function create()
    {
        return view('pages.komite-mutu.bank-spo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'jenis_spo' => 'required|in:SPO Utama,SPO Terkait',
            'nama_file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $jenisSpo = $request->jenis_spo;
        $uploadedFile = $request->file('nama_file');
        $originalName = $uploadedFile->getClientOriginalName();

        if ($jenisSpo === 'SPO Utama') {
            $targetPath = "bank-spo/$unit/" . $originalName;

            // Cek apakah file sudah ada di folder unit
            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
            }

            // Simpan file
            Storage::disk('public')->putFileAs("bank-spo/$unit", $uploadedFile, $originalName);

            // Simpan ke database
            BankSpo::create([
                'nama_file' => $originalName,
                'file_path' => $targetPath,
                'unit' => $unit,
                'jenis_spo' => 'SPO Utama',
            ]);
        }

        if ($jenisSpo === 'SPO Terkait') {
            // Cari SPO Utama berdasarkan nama file
            $utama = BankSpo::where('nama_file', $originalName)
                ->where('jenis_spo', 'SPO Utama')
                ->first();

            if (!$utama) {
                return back()->withErrors(['nama_file' => 'File SPO Utama tidak ditemukan. Harap unggah sebagai SPO Utama terlebih dahulu.']);
            }

            // Cek jika unit sudah punya SPO Terkait ini
            $duplikat = BankSpo::where('nama_file', $originalName)
                ->where('unit', $unit)
                ->where('jenis_spo', 'SPO Terkait')
                ->first();

            if ($duplikat) {
                return back()->withErrors(['nama_file' => 'Unit ini sudah memiliki SPO Terkait tersebut.']);
            }

            // Simpan relasi SPO Terkait (tidak upload ulang)
            BankSpo::create([
                'nama_file' => $originalName,
                'file_path' => $utama->file_path, // gunakan path dari SPO Utama
                'unit' => $unit,
                'jenis_spo' => 'SPO Terkait',
            ]);
        }

        return redirect()->route('komite-mutu.bank-spo.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $bank_spo = BankSpo::findOrFail($id);
        return view('pages.komite-mutu.bank-spo.detail', compact('bank_spo'));
    }

    public function showFile($id)
    {
        $spo = BankSpo::findOrFail($id);

        // Ambil path file dari SPO Utama jika SPO Terkait
        $filePath = $spo->file_source_id
            ? optional(BankSpo::find($spo->file_source_id))->file_path
            : $spo->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $unit = $spo->unit;
        $namaFile = $unit . '-' . pathinfo($filePath, PATHINFO_BASENAME);

        return Response::make(Storage::disk('public')->get($filePath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"'
        ]);
    }

    public function edit(string $id)
    {
        $bank_spo = BankSpo::findOrFail($id);
        return view('pages.komite-mutu.bank-spo.edit', compact('bank_spo'));
    }

    public function update(Request $request, string $id)
    {
        $bankSpo = BankSpo::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'jenis_spo' => 'required|in:SPO Utama,SPO Terkait',
            'nama_file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $jenisSpo = $request->jenis_spo;
        $uploadedFile = $request->file('nama_file');
        $originalName = $uploadedFile ? $uploadedFile->getClientOriginalName() : $bankSpo->nama_file;

        // Update untuk SPO Utama
        if ($jenisSpo === 'SPO Utama') {
            $targetPath = "bank-spo/$unit/" . $originalName;

            if ($uploadedFile) {
                // Hapus file lama jika path berbeda
                if ($bankSpo->file_path !== $targetPath && Storage::disk('public')->exists($bankSpo->file_path)) {
                    Storage::disk('public')->delete($bankSpo->file_path);
                }

                // Cek jika file baru sudah ada
                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
                }

                Storage::disk('public')->putFileAs("bank-spo/$unit", $uploadedFile, $originalName);
            } else {
                // Tidak upload file baru, maka copy dari file SPO Utama sebelumnya
                if (!Storage::disk('public')->exists($bankSpo->file_path)) {
                    return back()->withErrors(['nama_file' => 'File SPO sebelumnya tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['nama_file' => 'File sudah ada untuk unit ini.']);
                }

                // Salin file dari SPO sebelumnya ke folder unit baru
                $fileContent = Storage::disk('public')->get($bankSpo->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
            }

            $bankSpo->update([
                'nama_file' => $originalName,
                'file_path' => $targetPath,
                'unit' => $unit,
                'jenis_spo' => 'SPO Utama',
            ]);
        }

        // Update untuk SPO Terkait
        if ($jenisSpo === 'SPO Terkait') {
            // Tidak boleh upload file baru
            if ($uploadedFile) {
                return back()->withErrors(['nama_file' => 'SPO Terkait tidak boleh mengunggah file baru.']);
            }

            $utama = BankSpo::where('nama_file', $originalName)
                ->where('jenis_spo', 'SPO Utama')
                ->first();

            if (!$utama) {
                return back()->withErrors(['nama_file' => 'SPO Utama tidak ditemukan untuk file ini.']);
            }

            $bankSpo->update([
                'nama_file' => $originalName,
                'file_path' => $utama->file_path,
                'unit' => $unit,
                'jenis_spo' => 'SPO Terkait',
            ]);
        }

        return redirect()->route('komite-mutu.bank-spo.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bankSpo = BankSpo::findOrFail($id);

        // Jika SPO Utama, hapus juga file dan SPO Terkait yang terhubung
        if ($bankSpo->jenis_spo === 'SPO Utama') {
            // Hapus file dari storage jika masih ada
            if (Storage::disk('public')->exists($bankSpo->file_path)) {
                Storage::disk('public')->delete($bankSpo->file_path);
            }

            // Hapus SPO Terkait berdasarkan nama_file
            BankSpo::where('nama_file', $bankSpo->nama_file)
                ->where('jenis_spo', 'SPO Terkait')
                ->delete();
        }

        // Hapus data utama/terkait
        $bankSpo->delete();

        return redirect()->route('komite-mutu.bank-spo.index')->with('success', 'Data berhasil dihapus.');
    }
}
