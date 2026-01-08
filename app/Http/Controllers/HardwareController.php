<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hardware;
use Illuminate\Support\Facades\Storage;

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
        $hardware = Hardware::latest()->get();
        return view('pages.hardware.index', compact('hardware'));
    }

    public function create()
    {
        return view('pages.hardware.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'unit'         => 'required|string|max:255',
            'lantai'       => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'checklist'    => 'nullable|array',
            'tanda_tangan' => 'required|string',
        ]);

        if ($request->has('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/hardware/signature_' . time() . '.png';
            Storage::disk('public')->put($path, $decoded);

            $validated['tanda_tangan'] = 'storage/' . $path;
        }

        Hardware::create($validated);

        return redirect()->route('hardware.index')
            ->with('success', 'Data berhasil ditambahkan.');
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
            'nama'         => 'required|string|max:255',
            'unit'         => 'required|string|max:255',
            'lantai'       => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'checklist'    => 'nullable|array',
            'tanda_tangan' => 'nullable|string',
        ]);

        if ($request->filled('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/hardware/signature_' . time() . '.png';
            Storage::disk('public')->put($path, $decoded);

            if (
                $hardware->tanda_tangan &&
                Storage::disk('public')->exists(str_replace('storage/', '', $hardware->tanda_tangan))
            ) {
                Storage::disk('public')->delete(
                    str_replace('storage/', '', $hardware->tanda_tangan)
                );
            }

            $validated['tanda_tangan'] = 'storage/' . $path;
        } else {
            $validated['tanda_tangan'] = $hardware->tanda_tangan;
        }

        $hardware->update($validated);

        return redirect()->route('hardware.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Hardware $hardware)
    {
        if ($hardware->tanda_tangan) {
            $path = str_replace('storage/', '', $hardware->tanda_tangan);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $hardware->delete();

        return redirect()->route('hardware.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
