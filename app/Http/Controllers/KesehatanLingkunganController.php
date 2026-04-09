<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KesehatanLingkungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KesehatanLingkunganController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:kesehatan_lingkungan,read')->only(['index', 'show']);
        $this->middleware('permission:kesehatan_lingkungan,create')->only(['create', 'store']);
        $this->middleware('permission:kesehatan_lingkungan,update')->only(['edit', 'update']);
        $this->middleware('permission:kesehatan_lingkungan,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
        ]);

        $validator = Validator::make($request->all(), [
            'periode_dari'   => 'nullable|date_format:d-m-Y',
            'periode_sampai' => 'nullable|date_format:d-m-Y|after_or_equal:periode_dari',
        ], [
            'periode_sampai.after_or_equal' => 'Periode Sampai harus lebih besar atau sama dengan Periode Dari.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('komplain.kesehatan-lingkungan.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = KesehatanLingkungan::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $komplain = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.komplain.kesehatan-lingkungan.index', compact('komplain', 'isFiltered'));
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
            'dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $namaFile = 'kesehatan-lingkungan-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
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
            'nama' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'lokasi_masalah' => 'nullable|string',
            'jenis_hama' => 'nullable|string',
            'dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'nullable|in:Pending,In Progress,Done',
            'keterangan' => 'nullable|string|max:255',
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
