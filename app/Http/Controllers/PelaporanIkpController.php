<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelaporanIkp;
use Carbon\Carbon;

class PelaporanIkpController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pelaporan_ikp,read')->only(['index', 'show']);
        $this->middleware('permission:pelaporan_ikp,create')->only(['create', 'store']);
        $this->middleware('permission:pelaporan_ikp,update')->only(['edit', 'update']);
        $this->middleware('permission:pelaporan_ikp,delete')->only(['destroy']);
    }

    public function index()
    {
        $pelaporanIkp = PelaporanIkp::all();
        return view('pages.komite-mutu.pelaporan-ikp.index', compact('pelaporanIkp'));
    }

    public function create()
    {
        return view('pages.komite-mutu.pelaporan-ikp.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_rm' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'kelompok_umur' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|max:20',
            'penanggung_jawab' => 'required|string|max:255',
            'tanggal_masuk_rs' => 'required|date',
            'rincian_kejadian' => 'required|string|max:500',
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian' => 'required|string|max:50',
            'insiden' => 'required|string|max:255',
            'kronologis_kejadian' => 'required|string|max:1000',
            'jenis_kejadian' => 'required|string|max:255',
            'orang_pelapor' => 'required|string|max:255',
            'jenis_insiden' => 'required|string|max:255',
            'insiden_pasien' => 'required|string|max:255',
            'lokasi_insiden' => 'required|string|max:255',
            'jenis_spesialisasi_pasien' => 'required|string|max:255',
            'unit_terkait' => 'required|string|max:255',
            'akibat_insiden' => 'required|string|max:1000',
            'tindakan_yang_dilakukan' => 'required|string|max:1000',
            'tindakan_dilakukan_oleh' => 'required|string|max:255',
            'kejadian_serupa' => 'required|string|max:255',
            'grading_risiko' => 'required|string|max:100',
        ]);

        $tanggal_lahir = Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir)->format('Y-m-d');
        $validated['tanggal_lahir'] = $tanggal_lahir;

        $tanggal_masuk_rs = Carbon::createFromFormat('d-m-Y', $request->tanggal_masuk_rs)->format('Y-m-d');
        $validated['tanggal_masuk_rs'] = $tanggal_masuk_rs;

        $tanggal_kejadian = Carbon::createFromFormat('d-m-Y', $request->tanggal_kejadian)->format('Y-m-d');
        $validated['tanggal_kejadian'] = $tanggal_kejadian;

        PelaporanIkp::create($validated);

        return redirect()->route('komite-mutu.pelaporan-ikp.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);
        return view('pages.komite-mutu.pelaporan-ikp.detail', compact('pelaporanIkp'));
    }

    public function edit(string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);
        return view('pages.komite-mutu.pelaporan-ikp.edit', compact('pelaporanIkp'));
    }

    public function update(Request $request, string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_rm' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'kelompok_umur' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|max:20',
            'penanggung_jawab' => 'required|string|max:255',
            'tanggal_masuk_rs' => 'required|date',
            'rincian_kejadian' => 'required|string|max:500',
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian' => 'required|string|max:50',
            'insiden' => 'required|string|max:255',
            'kronologis_kejadian' => 'required|string|max:1000',
            'jenis_kejadian' => 'required|string|max:255',
            'orang_pelapor' => 'required|string|max:255',
            'jenis_insiden' => 'required|string|max:255',
            'insiden_pasien' => 'required|string|max:255',
            'lokasi_insiden' => 'required|string|max:255',
            'jenis_spesialisasi_pasien' => 'required|string|max:255',
            'unit_terkait' => 'required|string|max:255',
            'akibat_insiden' => 'required|string|max:1000',
            'tindakan_yang_dilakukan' => 'required|string|max:1000',
            'tindakan_dilakukan_oleh' => 'required|string|max:255',
            'kejadian_serupa' => 'required|string|max:255',
            'grading_risiko' => 'required|string|max:100',
        ]);

        $tanggal_lahir = Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir)->format('Y-m-d');
        $validated['tanggal_lahir'] = $tanggal_lahir;

        $tanggal_masuk_rs = Carbon::createFromFormat('d-m-Y', $request->tanggal_masuk_rs)->format('Y-m-d');
        $validated['tanggal_masuk_rs'] = $tanggal_masuk_rs;

        $tanggal_kejadian = Carbon::createFromFormat('d-m-Y', $request->tanggal_kejadian)->format('Y-m-d');
        $validated['tanggal_kejadian'] = $tanggal_kejadian;

        $pelaporanIkp->update($validated);

        return redirect()->route('komite-mutu.pelaporan-ikp.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);
        $pelaporanIkp->delete();

        return redirect()->route('komite-mutu.pelaporan-ikp.index')->with('success', 'Data berhasil dihapus.');
    }
}
