<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankSpo;
use Illuminate\Support\Facades\Storage;

class BankSpoController extends Controller
{

    public function index()
    {
        $bank_spo = BankSpo::all();
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

        return redirect()->route('komite-mutu.bank-spo.index')->with('success', 'SPO berhasil disimpan.');
    }

    public function show(string $id)
    {
        $bank_spo = BankSpo::findOrFail($id);
        return view('pages.komite-mutu.bank-spo.detail', compact('bank_spo'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
