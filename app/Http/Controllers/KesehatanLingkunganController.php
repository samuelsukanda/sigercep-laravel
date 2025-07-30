<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KesehatanLingkungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class KesehatanLingkunganController extends Controller
{

    public function index(Request $request)
    {
        $komplain = KesehatanLingkungan::all();

        $query = KesehatanLingkungan::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $komplain = $query->latest()->get();

        return view('pages.komplain.kesehatan-lingkungan.index', compact('komplain'));
    }

    public function create()
    {
        return view('pages.komplain.kesehatan-lingkungan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'lokasi_masalah' => 'required|string',
            'jenis_hama' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $namaFile = 'kesehatan-lingkungan' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/kesehatan-lingkungan', $namaFile, 'public');
            $validated['dokumentasi'] = $path;
        }

        KesehatanLingkungan::create($validated);

        return redirect()->route('komplain.kesehatan-lingkungan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $komplain = KesehatanLingkungan::findOrFail($id);
        return view('pages.komplain.kesehatan-lingkungan.detail', compact('komplain'));
    }

    public function edit(string $id)
    {
        $komplain = KesehatanLingkungan::findOrFail($id);
        return view('pages.komplain.kesehatan-lingkungan.edit', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'lokasi_masalah' => 'required|string',
            'jenis_hama' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $komplain = KesehatanLingkungan::findOrFail($id);

        if ($request->hasFile('dokumentasi')) {
            if ($komplain->dokumentasi && Storage::disk('public')->exists($komplain->dokumentasi)) {
                Storage::disk('public')->delete($komplain->dokumentasi);
            }

            $file = $request->file('dokumentasi');
            $namaFile = 'kesehatan-lingkungan-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/kesehatan-lingkungan', $namaFile, 'public');
            $validated['dokumentasi'] = $path;
        }

        $komplain->update($validated);

        return redirect()->route('komplain.kesehatan-lingkungan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $komplain = KesehatanLingkungan::findOrFail($id);

        if ($komplain->dokumentasi && Storage::disk('public')->exists($komplain->dokumentasi)) {
            Storage::disk('public')->delete($komplain->dokumentasi);
        }

        $komplain->delete();

        return redirect()->route('komplain.kesehatan-lingkungan.index')->with('success', 'Data berhasil dihapus.');
    }
}
