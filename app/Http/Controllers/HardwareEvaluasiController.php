<?php

namespace App\Http\Controllers;

use App\Models\HardwareEvaluasi;
use Illuminate\Http\Request;

class HardwareEvaluasiController extends Controller
{
    /**
     * Tampilkan halaman daftar semua evaluasi.
     */
    public function index()
    {
        return view('pages.hardware.evaluasi');
    }

    /**
     * Ambil data evaluasi untuk bulan tertentu (GET /hardware/evaluasi/data?bulan=YYYY-MM).
     */
    public function getData(Request $request)
    {
        $bulan = $request->query('bulan');

        if (!$bulan) {
            return response()->json(['rows' => []]);
        }

        $rows = HardwareEvaluasi::where('bulan', $bulan)
            ->orderBy('nomor')
            ->get(['nomor', 'kendala', 'rtl']);

        return response()->json(['rows' => $rows]);
    }

    /**
     * Simpan/update evaluasi untuk bulan tertentu (POST /hardware/evaluasi/simpan).
     * Strategi: hapus semua baris bulan itu, lalu insert ulang.
     */
    public function simpan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string|size:7', // YYYY-MM
            'rows'  => 'required|array',
            'rows.*.kendala' => 'nullable|string',
            'rows.*.rtl'     => 'nullable|string',
        ]);

        $bulan = $request->bulan;
        $rows  = $request->rows;

        // Hapus semua data lama untuk bulan ini
        HardwareEvaluasi::where('bulan', $bulan)->delete();

        // Insert baris baru (hanya yang ada isinya)
        $insert = [];
        $nomor  = 1;
        foreach ($rows as $row) {
            $kendala = trim($row['kendala'] ?? '');
            $rtl     = trim($row['rtl'] ?? '');
            if ($kendala === '' && $rtl === '') continue;

            $insert[] = [
                'bulan'      => $bulan,
                'nomor'      => $nomor++,
                'kendala'    => $kendala ?: null,
                'rtl'        => $rtl ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insert)) {
            HardwareEvaluasi::insert($insert);
        }

        return response()->json(['success' => true, 'tersimpan' => count($insert)]);
    }

    /**
     * Ambil semua bulan yang memiliki data evaluasi (GET /hardware/evaluasi/semua).
     */
    public function semuaData()
    {
        $data = HardwareEvaluasi::orderByDesc('bulan')
            ->orderBy('nomor')
            ->get(['bulan', 'nomor', 'kendala', 'rtl']);

        // Kelompokkan per bulan
        $grouped = [];
        foreach ($data as $row) {
            $grouped[$row->bulan][] = [
                'nomor'   => $row->nomor,
                'kendala' => $row->kendala,
                'rtl'     => $row->rtl,
            ];
        }

        // Format sebagai array terurut
        $result = [];
        foreach ($grouped as $bulan => $rows) {
            $result[] = ['bulan' => $bulan, 'rows' => $rows];
        }

        return response()->json(['data' => $result]);
    }

    /**
     * Hapus semua data evaluasi untuk bulan tertentu (DELETE /hardware/evaluasi/{bulan}).
     */
    public function hapusBulan(string $bulan)
    {
        HardwareEvaluasi::where('bulan', $bulan)->delete();
        return response()->json(['success' => true]);
    }
}
