<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hardware;

class HardwareController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:hardware,read')->only(['index', 'show']);
        $this->middleware('permission:hardware,create')->only(['create', 'store']);
        $this->middleware('permission:hardware,update')->only(['edit', 'update']);
        $this->middleware('permission:hardware,delete')->only(['destroy']);
    }

    public function index()
    {
        $hardware = Hardware::all();
        return view('pages.hardware.index', compact('hardware'));
    }

    public function create()
    {
        return view('pages.hardware.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'unit'   => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'checklist' => 'nullable|array',
        ]);

        Hardware::create([
            'nama'      => $validated['nama'],
            'unit'      => $validated['unit'],
            'lantai'    => $validated['lantai'],
            'tanggal'   => $validated['tanggal'],
            'checklist' => $validated['checklist'],
        ]);


        return redirect()->route('hardware.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Hardware $hardware)
    {
        return view('pages.hardware.detail', compact('hardware'));
    }

    public function edit(Hardware $hardware)
    {
        return view('pages.hardware.edit', compact('hardware'));
    }

    public function update(Request $request, Hardware $hardware)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'unit'   => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'checklist' => 'nullable|array',
        ]);

        $hardware->update([
            'nama'     => $validated['nama'],
            'unit'     => $validated['unit'],
            'lantai'   => $validated['lantai'],
            'tanggal'  => $validated['tanggal'],
            'checklist' => $validated['checklist'],
        ]);

        return redirect()->route('hardware.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Hardware $hardware)
    {
        $hardware->delete();
        return redirect()->route('hardware.index')->with('success', 'Data berhasil dihapus.');
    }
}
