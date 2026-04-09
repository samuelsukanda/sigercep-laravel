<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesainGrafis;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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
            return redirect()->route('desain-grafis.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = DesainGrafis::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $desain = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.desain-grafis.index', compact('desain', 'isFiltered'));
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
