<?php

namespace App\Http\Controllers;

use App\Models\HardwareEvaluasi;
use Illuminate\Http\Request;

class HardwareEvaluasiController extends Controller
{

    public function index()
    {
        return view('pages.hardware.evaluasi');
    }

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

    public function simpan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string|size:7',
            'rows'  => 'required|array',
            'rows.*.kendala' => 'nullable|string',
            'rows.*.rtl'     => 'nullable|string',
        ]);

        $bulan = $request->bulan;
        $rows  = $request->rows;

        HardwareEvaluasi::where('bulan', $bulan)->delete();

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

    public function semuaData()
    {
        $data = HardwareEvaluasi::orderByDesc('bulan')
            ->orderBy('nomor')
            ->get(['bulan', 'nomor', 'kendala', 'rtl']);

        $grouped = [];
        foreach ($data as $row) {
            $grouped[$row->bulan][] = [
                'nomor'   => $row->nomor,
                'kendala' => $row->kendala,
                'rtl'     => $row->rtl,
            ];
        }

        $result = [];
        foreach ($grouped as $bulan => $rows) {
            $result[] = ['bulan' => $bulan, 'rows' => $rows];
        }

        return response()->json(['data' => $result]);
    }

    public function hapusBulan(string $bulan)
    {
        HardwareEvaluasi::where('bulan', $bulan)->delete();
        return response()->json(['success' => true]);
    }
}
