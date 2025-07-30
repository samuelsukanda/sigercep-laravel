<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toner;
use Illuminate\Support\Facades\Storage;

class TonerController extends Controller
{

    public function index(Request $request)
    {
        $toner = Toner::all();

        $query = Toner::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $toner = $query->latest()->get();

        return view('pages.toner.index', compact('toner'));
    }

    public function create()
    {
        return view('pages.toner.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'toner' => 'required|string|max:50',
            'jumlah' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'tanda_tangan' => 'required|string',
        ]);

        if ($request->has('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/toner/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            $validated['tanda_tangan'] = 'storage/' . $path;
        }

        Toner::create($validated);

        return redirect()->route('toner.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $toner = Toner::findOrFail($id);
        return view('pages.toner.detail', compact('toner'));
    }

    public function edit(string $id)
    {
        $toner = Toner::findOrFail($id);
        return view('pages.toner.edit', compact('toner'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'toner' => 'required|string|max:50',
            'jumlah' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'tanda_tangan' => 'nullable|string',
        ]);

        $toner = Toner::findOrFail($id);

        if ($request->filled('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/toner/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            if ($toner->tanda_tangan && Storage::disk('public')->exists(str_replace('storage/', '', $toner->tanda_tangan))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $toner->tanda_tangan));
            }

            $validated['tanda_tangan'] = 'storage/' . $path;
        } else {
            $validated['tanda_tangan'] = $toner->tanda_tangan;
        }

        $toner->update($validated);

        return redirect()->route('toner.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $toner = Toner::findOrFail($id);

        if ($toner->tanda_tangan) {
            $signaturePath = str_replace('storage/', '', $toner->tanda_tangan);
            if (Storage::disk('public')->exists($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }
        }

        $toner->delete();

        return redirect()->route('toner.index')->with('success', 'Data berhasil dihapus.');
    }
}
