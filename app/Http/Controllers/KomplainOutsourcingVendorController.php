<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomplainOutsourcingVendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class KomplainOutsourcingVendorController extends Controller
{

    public function index()
    {
        $komplain = KomplainOutsourcingVendor::all();
        return view('pages.komplain.outsourcing-vendor.index', compact('komplain'));
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'jam' => 'required',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'area' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
