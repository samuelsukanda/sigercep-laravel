<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomplainOutsourcingVendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KomplainOutsourcingVendorController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:outsourcing_vendor,read')->only(['index', 'show']);
        $this->middleware('permission:outsourcing_vendor,create')->only(['create', 'store']);
        $this->middleware('permission:outsourcing_vendor,update')->only(['edit', 'update']);
        $this->middleware('permission:outsourcing_vendor,delete')->only(['destroy']);
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
            return redirect()->route('komplain.outsourcing-vendor.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = KomplainOutsourcingVendor::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $komplain = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.komplain.outsourcing-vendor.index', compact('komplain', 'isFiltered'));
    }

    public function create()
    {
        return view('pages.komplain.outsourcing-vendor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'jam' => 'required',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'area' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'komplain-outsourcing-vendor-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-outsourcing-vendor', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        KomplainOutsourcingVendor::create($validated);

        return redirect()->route('komplain.outsourcing-vendor.index')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $komplain = KomplainOutsourcingVendor::findOrFail($id);
        return view('pages.komplain.outsourcing-vendor.edit', compact('komplain'));
    }

    public function show($id)
    {
        $komplain = KomplainOutsourcingVendor::findOrFail($id);
        return view('pages.komplain.outsourcing-vendor.detail', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'tujuan_unit' => 'nullable|string|max:50',
            'jam' => 'nullable',
            'tanggal' => 'nullable|date',
            'kendala' => 'nullable|string',
            'area' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'nullable|in:Pending,In Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $komplain = KomplainOutsourcingVendor::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
                Storage::disk('public')->delete($komplain->foto);
            }

            $file = $request->file('foto');
            $namaFile = 'komplain-outsourcing-vendor-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-outsourcing-vendor', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        $komplain->update($validated);

        return redirect()->route('komplain.outsourcing-vendor.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $komplain = KomplainOutsourcingVendor::findOrFail($id);

        if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
            Storage::disk('public')->delete($komplain->foto);
        }

        $komplain->delete();

        return redirect()->route('komplain.outsourcing-vendor.index')->with('success', 'Data berhasil dihapus.');
    }
}
