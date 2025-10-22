<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesainGrafis;

class DesainGrafisController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:desain_grafis,read')->only(['index', 'show']);
        $this->middleware('permission:desain_grafis,create')->only(['create', 'store']);
        $this->middleware('permission:desain_grafis,update')->only(['edit', 'update']);
        $this->middleware('permission:desain_grafis,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $desain = DesainGrafis::all();

        $query = DesainGrafis::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $desain = $query->latest()->get();

        return view('pages.desain-grafis.index', compact('desain'));
    }

    public function create()
    {
        return view('pages.desain-grafis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'desain' => 'required|string|max:50',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'panjang' => 'nullable|string|max:50',
            'tinggi' => 'nullable|string|max:50',
            'satuan' => 'nullable|string|max:50',
            'menit' => 'nullable|string|max:50',
            'detik' => 'nullable|string|max:50',
        ]);

        if ($validated['desain'] === 'Video') {
            $validated['panjang'] = null;
            $validated['tinggi'] = null;
            $validated['satuan'] = null;
        } else {
            $validated['menit'] = null;
            $validated['detik'] = null;
        }

        DesainGrafis::create($validated);

        return redirect()->route('desain-grafis.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show($id)
    {
        $desain = DesainGrafis::findOrFail($id);
        return view('pages.desain-grafis.detail', compact('desain'));
    }

    public function edit($id)
    {
        $desain = DesainGrafis::findOrFail($id);
        return view('pages.desain-grafis.edit', compact('desain'));
    }

    public function update(Request $request, $id)
    {
        $desain = DesainGrafis::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'desain' => 'required|string|max:50',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'panjang' => 'nullable|string|max:50',
            'tinggi' => 'nullable|string|max:50',
            'satuan' => 'nullable|string|max:50',
            'menit' => 'nullable|string|max:50',
            'detik' => 'nullable|string|max:50',
        ]);

        $updateData = $validated;

        if ($validated['desain'] === 'Video') {
            $updateData['panjang'] = null;
            $updateData['tinggi'] = null;
            $updateData['satuan'] = null;
        } else {
            $updateData['menit'] = null;
            $updateData['detik'] = null;
        }

        $desain->update($updateData);

        return redirect()->route('desain-grafis.index')->with('success', 'Data berhasil diperbarui.');
    }


    public function destroy(string $id)
    {
        $desain = DesainGrafis::findOrFail($id);

        $desain->delete();

        return redirect()->route('desain-grafis.index')->with('success', 'Data berhasil dihapus.');
    }
}
