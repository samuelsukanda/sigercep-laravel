<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Indicator;
use App\Models\IndicatorValue;

class IndicatorValueController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:mutu,read')->only(['editBulk']);
        $this->middleware('permission:mutu,update')->only(['updateBulk']);
    }

    public function editBulk(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $jenis = $request->get('jenis', 'INM');
        $jenisOptions = ['INM', 'IMPRS', 'IMP-UNIT', 'IMP-VENDOR', 'IMP-KOMITE'];
        $tahunOptions = [2025, 2026, 2027];

        $bulanNama = [
            1  => 'Januari',  2  => 'Februari',  3  => 'Maret',
            4  => 'April',    5  => 'Mei',        6  => 'Juni',
            7  => 'Juli',     8  => 'Agustus',    9  => 'September',
            10 => 'Oktober',  11 => 'November',   12 => 'Desember',
        ];

        $indicators = Indicator::where('jenis_indikator', $jenis)
            ->whereHas('targets', fn($q) => $q->where('tahun', $tahun))
            ->with(['targets' => fn($q) => $q->where('tahun', $tahun)])
            ->orderByRaw("CAST(no_urut AS UNSIGNED)")
            ->get();

        $existing = IndicatorValue::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->whereIn('indicator_id', $indicators->pluck('id'))
            ->get()
            ->keyBy('indicator_id');

        return view('pages.komite-mutu.indicator-values.bulk-edit', compact(
            'tahun', 'bulan', 'jenis', 'jenisOptions', 'tahunOptions',
            'bulanNama', 'indicators', 'existing'
        ));
    }

    public function updateBulk(Request $request)
    {
        $request->validate([
            'tahun'                    => 'required|integer|min:2020|max:2030',
            'bulan'                    => 'required|integer|min:1|max:12',
            'values'                   => 'nullable|array',
            'values.*.nilai'           => 'nullable|numeric',
            'values.*.numerator'       => 'nullable|numeric',
            'values.*.denominator'     => 'nullable|numeric',
        ]);

        foreach (($request->values ?? []) as $indicatorId => $data) {
            $nilaiInput  = $data['nilai']       ?? null;
            $numerator   = isset($data['numerator'])   && $data['numerator']   !== '' ? (float) $data['numerator']   : null;
            $denominator = isset($data['denominator']) && $data['denominator'] !== '' ? (float) $data['denominator'] : null;

            if ($numerator !== null && $denominator !== null && $denominator > 0) {
                $nilai = round(($numerator / $denominator) * 100, 2);
            } elseif ($nilaiInput !== null && $nilaiInput !== '') {
                $nilai = (float) $nilaiInput;
            } else {
                IndicatorValue::where([
                    'indicator_id' => $indicatorId,
                    'tahun'        => $request->tahun,
                    'bulan'        => $request->bulan,
                ])->delete();
                continue;
            }

            IndicatorValue::updateOrCreate(
                [
                    'indicator_id' => $indicatorId,
                    'tahun'        => $request->tahun,
                    'bulan'        => $request->bulan,
                ],
                [
                    'nilai'        => $nilai,
                    'numerator'    => $numerator,
                    'denominator'  => $denominator,
                ]
            );
        }

        return redirect()->route('indicator-values.bulk-edit', [
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'jenis' => $request->jenis,
        ])->with('success', 'Data capaian bulan berhasil disimpan.');
    }
}
