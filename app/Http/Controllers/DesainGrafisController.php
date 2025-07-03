<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesainGrafis;

class DesainGrafisController extends Controller
{
    public function index()
    {
        $desain = DesainGrafis::all();
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

        // Reset field yang tidak digunakan berdasarkan jenis desain
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
