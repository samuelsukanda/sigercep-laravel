<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutu;

class MutuController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:mutu,read')->only(['index', 'show']);
        $this->middleware('permission:mutu,create')->only(['create', 'store']);
        $this->middleware('permission:mutu,update')->only(['edit', 'update']);
        $this->middleware('permission:mutu,delete')->only(['destroy']);
    }

    public function index()
    {
        $mutu = Mutu::all();
        return view('pages.komite-mutu.mutu.index', compact('mutu'));
    }

    public function create()
    {
        return view('pages.komite-mutu.mutu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'indikator' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'unit' => 'required|string|max:100',
            'pj_data' => 'required|string|max:100',
            'numerator' => 'required|string|max:100',
            'penumerator' => 'required|string|max:100',
            'capaian' => 'required|string|max:100',
        ]);

        Mutu::create($validated);

        return redirect()->route('komite-mutu.mutu.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $mutu = Mutu::findOrFail($id);
        return view('pages.komite-mutu.mutu.detail', compact('mutu'));
    }

    public function edit(string $id)
    {
        $mutu = Mutu::findOrFail($id);
        return view('pages.komite-mutu.mutu.edit', compact('mutu'));
    }

    public function update(Request $request, $id)
    {
        $mutu = Mutu::findOrFail($id);

        $validated = $request->validate([
            'indikator' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'unit' => 'required|string|max:100',
            'pj_data' => 'required|string|max:100',
            'numerator' => 'required|string|max:100',
            'penumerator' => 'required|string|max:100',
            'capaian' => 'required|string|max:100',
        ]);

        $mutu->update($validated);

        return redirect()->route('komite-mutu.mutu.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $mutu = Mutu::findOrFail($id);
        $mutu->delete();

        return redirect()->route('komite-mutu.mutu.index')->with('success', 'Data berhasil dihapus.');
    }
}
