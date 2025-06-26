<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManajemenRisiko;

class ManajemenRisikoController extends Controller
{

    public function index()
    {
        $mutu = ManajemenRisiko::all();
        return view('pages.komite-mutu.manajemen-risiko.index', compact('mutu'));
    }

    public function create()
    {
        return view('pages.komite-mutu.manajemen-risiko.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'uraian' => 'required|string|max:255',
            'dampak' => 'required|integer|min:1|max:25',
            'kemungkinan' => 'required|integer|min:1|max:5',
            'nilai' => 'required|integer',
            'keterangan' => 'nullable|string|max:255',
        ]);

        ManajemenRisiko::create($validated);

        return redirect()->route('komite-mutu.manajemen-risiko.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $mutu = ManajemenRisiko::findOrFail($id);
        return view('pages.komite-mutu.manajemen-risiko.detail', compact('mutu'));
    }

    public function edit(string $id)
    {
        $mutu = ManajemenRisiko::findOrFail($id);
        return view('pages.komite-mutu.manajemen-risiko.edit', compact('mutu'));
    }

    public function update(Request $request, string $id)
    {
        $mutu = ManajemenRisiko::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'uraian' => 'required|string|max:255',
            'dampak' => 'required|integer|min:1|max:5',
            'kemungkinan' => 'required|integer|min:1|max:5',
            'nilai' => 'required|integer|min:1|max:25',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $mutu->update($validated);

        return redirect()->route('komite-mutu.manajemen-risiko.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $mutu = ManajemenRisiko::findOrFail($id);
        $mutu->delete();

        return redirect()->route('komite-mutu.manajemen-risiko.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
