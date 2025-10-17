<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomplainIpsrs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class KomplainIpsrsController extends Controller
{

    public function index(Request $request)
    {
        $komplain = KomplainIpsrs::all();

        $query = KomplainIpsrs::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $komplain = $query->latest()->get();

        return view('pages.komplain.ipsrs.index', compact('komplain'));
    }

    public function create()
    {
        return view('pages.komplain.ipsrs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'komplain-ipsrs-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-ipsrs', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        KomplainIpsrs::create($validated);

        return redirect()->route('komplain.ipsrs.index')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);
        return view('pages.komplain.ipsrs.edit', compact('komplain'));
    }

    public function show($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);
        return view('pages.komplain.ipsrs.detail', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $komplain = KomplainIpsrs::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
                Storage::disk('public')->delete($komplain->foto);
            }

            $file = $request->file('foto');
            $namaFile = 'komplain-ipsrs-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-ipsrs', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        $komplain->update($validated);

        return redirect()->route('komplain.ipsrs.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);

        if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
            Storage::disk('public')->delete($komplain->foto);
        }

        $komplain->delete();

        return redirect()->route('komplain.ipsrs.index')->with('success', 'Data berhasil dihapus.');
    }
}
