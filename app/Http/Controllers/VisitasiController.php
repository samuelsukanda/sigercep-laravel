<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VisitasiController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:visitasi,read')->only(['index', 'show']);
        $this->middleware('permission:visitasi,create')->only(['create', 'store']);
        $this->middleware('permission:visitasi,update')->only(['edit', 'update']);
        $this->middleware('permission:visitasi,delete')->only(['destroy']);
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
            return redirect()->route('visitasi.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = Visitasi::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $visitasi = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.visitasi.index', compact('visitasi', 'isFiltered'));
    }

    public function create()
    {
        return view('pages.visitasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'tim' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'visitasi-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/visitasi', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        Visitasi::create($validated);

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);
        return view('pages.visitasi.detail', compact('visitasi'));
    }

    public function edit(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);
        return view('pages.visitasi.edit', compact('visitasi'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'tim' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $visitasi = Visitasi::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($visitasi->foto && Storage::disk('public')->exists($visitasi->foto)) {
                Storage::disk('public')->delete($visitasi->foto);
            }

            $file = $request->file('foto');
            $namaFile = 'visitasi-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/visitasi', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        $visitasi->update($validated);

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);

        if ($visitasi->foto && Storage::disk('public')->exists($visitasi->foto)) {
            Storage::disk('public')->delete($visitasi->foto);
        }

        $visitasi->delete();

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
