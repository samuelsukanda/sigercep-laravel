<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Indicator;
use App\Models\IndicatorTarget;
use App\Models\IndicatorValue;
use Illuminate\Support\Facades\DB;

class IndicatorExcelSeeder extends Seeder
{
    /**
     * Bulan kolom map untuk sheet IMUT 2026 (col index 0-based):
     *  JAN=4, FEB=5, MAR=6, APR=8, MEI=9, JUN=10,
     *  JUL=12, AGT=13, SEP=14, OKT=16, NOV=17, DES=18
     */
    private $colMap2026 = [
        1 => 4,   // JAN
        2 => 5,   // FEB
        3 => 6,   // MAR
        4 => 8,   // APR
        5 => 9,   // MEI
        6 => 10,  // JUN
        7 => 12,  // JUL
        8 => 13,  // AGT
        9 => 14,  // SEP
        10 => 16, // OKT
        11 => 17, // NOV
        12 => 18, // DES
    ];

    /**
     * Bulan kolom map untuk sheet 2025:
     *  JAN=22, FEB=23, MAR=24, APR=26, MEI=27, JUN=28,
     *  JUL=30, AGT=31, SEP=32, OKT=34, NOV=35, DES=36
     */
    private $colMap2025 = [
        1 => 22,  // JAN
        2 => 23,  // FEB
        3 => 24,  // MAR
        4 => 26,  // APR
        5 => 27,  // MEI
        6 => 28,  // JUN
        7 => 30,  // JUL
        8 => 31,  // AGT
        9 => 32,  // SEP
        10 => 34, // OKT
        11 => 35, // NOV
        12 => 36, // DES
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        IndicatorValue::truncate();
        IndicatorTarget::truncate();
        Indicator::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $filePath = 'C:\Users\Admin\Herd\sigercep\public\assets\file\MONITORING CAPAIAN INDIKATOR MUTU RS HAMORI.xlsx';

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: $filePath");
            return;
        }

        $this->command->info('Loading spreadsheet...');
        $spreadsheet = IOFactory::load($filePath);

        // ================================================================
        // PARSE SHEET: IMUT 2026
        // ================================================================
        $this->command->info('Parsing sheet IMUT 2026...');
        $sheet2026 = $spreadsheet->getSheetByName('IMUT 2026');
        $rows2026 = $sheet2026->toArray();

        // INM: baris 22-34 (index 21-33), header di index 20
        $this->parseSections($rows2026, [
            ['jenis' => 'INM',        'startIdx' => 21, 'endIdx' => 33, 'noCol' => 0, 'pjCol' => 1, 'namaCol' => 2, 'targetCol' => 3, 'unitCol' => null],
            ['jenis' => 'IMPRS',      'startIdx' => 36, 'endIdx' => 55, 'noCol' => 0, 'pjCol' => 1, 'namaCol' => 2, 'targetCol' => 3, 'unitCol' => null],
            ['jenis' => 'IMP-UNIT',   'startIdx' => 57, 'endIdx' => 110,'noCol' => 0, 'pjCol' => 1, 'namaCol' => 2, 'targetCol' => 3, 'unitCol' => null],
            ['jenis' => 'IMP-VENDOR', 'startIdx' => 110,'endIdx' => 119,'noCol' => 0, 'pjCol' => 1, 'namaCol' => 2, 'targetCol' => 3, 'unitCol' => null],
            ['jenis' => 'IMP-KOMITE', 'startIdx' => 119,'endIdx' => 130,'noCol' => 0, 'pjCol' => 1, 'namaCol' => 2, 'targetCol' => 3, 'unitCol' => null],
        ], 2026, $this->colMap2026);

        // ================================================================
        // PARSE SHEET: 2025
        // ================================================================
        $this->command->info('Parsing sheet 2025...');
        $sheet2025 = $spreadsheet->getSheetByName('2025');
        $rows2025 = $sheet2025->toArray();

        // IMP-UNIT: baris 26-92 (index 25-91), cols: no=5, pj=6 (unit), nama=8, target=20
        // Catatan: section 2025 berbeda layout - no ada di col 5, PJ di col 7, nama indikator di col 8
        $this->parseSections2025($rows2025, [
            ['jenis' => 'IMP-UNIT',   'startIdx' => 25, 'endIdx' => 91,  'noCol' => 5, 'pjCol' => 7, 'namaCol' => 8, 'targetCol' => 20, 'unitCol' => 6],
            ['jenis' => 'IMPRS',      'startIdx' => 93, 'endIdx' => 106, 'noCol' => 5, 'pjCol' => 7, 'namaCol' => 8, 'targetCol' => 20, 'unitCol' => 6],
            ['jenis' => 'INM',        'startIdx' => 109,'endIdx' => 122, 'noCol' => 5, 'pjCol' => 6, 'namaCol' => 8, 'targetCol' => 20, 'unitCol' => null],
        ], 2025, $this->colMap2025);

        $this->command->info('Seeding selesai!');
        $this->command->info('Total indicators: ' . Indicator::count());
        $this->command->info('Total targets: ' . IndicatorTarget::count());
        $this->command->info('Total values: ' . IndicatorValue::count());
    }

    /**
     * Parse sections untuk sheet 2026 (layout: no=0, pj=1, nama=2, target=3).
     */
    private function parseSections(array $rows, array $sections, int $tahun, array $colMap): void
    {
        foreach ($sections as $section) {
            $jenis      = $section['jenis'];
            $startIdx   = $section['startIdx'];
            $endIdx     = min($section['endIdx'], count($rows) - 1);
            $noCol      = $section['noCol'];
            $pjCol      = $section['pjCol'];
            $namaCol    = $section['namaCol'];
            $targetCol  = $section['targetCol'];

            $counter = 1;

            for ($i = $startIdx; $i <= $endIdx; $i++) {
                $row = $rows[$i] ?? [];
                $no   = trim($row[$noCol] ?? '');
                $pj   = trim($row[$pjCol] ?? '');
                $nama = trim($row[$namaCol] ?? '');

                // Skip baris kosong atau baris header
                if (empty($nama) || $no === 'NO' || !is_numeric($no)) {
                    continue;
                }

                $targetRaw = trim($row[$targetCol] ?? '');
                $targetVal = $this->parseTarget($targetRaw);

                $indicator = Indicator::firstOrCreate(
                    [
                        'nama_indikator' => $nama,
                        'jenis_indikator' => $jenis,
                    ],
                    [
                        'no_urut'        => $no,
                        'pj'             => $pj,
                        'unit_terkait'   => null,
                    ]
                );

                IndicatorTarget::updateOrCreate(
                    ['indicator_id' => $indicator->id, 'tahun' => $tahun],
                    ['target_value' => $targetVal, 'target_type' => 'persen']
                );

                foreach ($colMap as $bulan => $colIdx) {
                    $nilaiRaw = trim($row[$colIdx] ?? '');
                    $nilai = $this->parseNilai($nilaiRaw);
                    if ($nilai !== null) {
                        IndicatorValue::updateOrCreate(
                            ['indicator_id' => $indicator->id, 'tahun' => $tahun, 'bulan' => $bulan],
                            ['nilai' => $nilai, 'numerator' => null, 'denominator' => null]
                        );
                    }
                }

                $counter++;
            }

            $this->command->line("  [$jenis] $tahun: $counter indikator diproses");
        }
    }

    /**
     * Parse sections untuk sheet 2025 (layout berbeda: no=5, pj=7, nama=8, target=20).
     */
    private function parseSections2025(array $rows, array $sections, int $tahun, array $colMap): void
    {
        foreach ($sections as $section) {
            $jenis     = $section['jenis'];
            $startIdx  = $section['startIdx'];
            $endIdx    = min($section['endIdx'], count($rows) - 1);
            $noCol     = $section['noCol'];
            $pjCol     = $section['pjCol'];
            $namaCol   = $section['namaCol'];
            $targetCol = $section['targetCol'];
            $unitCol   = $section['unitCol'];

            $counter = 0;

            for ($i = $startIdx; $i <= $endIdx; $i++) {
                $row  = $rows[$i] ?? [];
                $no   = trim($row[$noCol] ?? '');
                $pj   = trim($row[$pjCol] ?? '');
                $nama = trim($row[$namaCol] ?? '');
                $unit = $unitCol !== null ? trim($row[$unitCol] ?? '') : null;

                // Skip baris kosong, header, atau baris departemen
                if (empty($nama) || $no === 'No' || !is_numeric($no)) {
                    continue;
                }

                $targetRaw = trim($row[$targetCol] ?? '');
                $targetVal = $this->parseTarget($targetRaw);

                $indicator = Indicator::firstOrCreate(
                    [
                        'nama_indikator'  => $nama,
                        'jenis_indikator' => $jenis,
                    ],
                    [
                        'no_urut'       => $no,
                        'pj'            => $pj,
                        'unit_terkait'  => $unit,
                    ]
                );

                IndicatorTarget::updateOrCreate(
                    ['indicator_id' => $indicator->id, 'tahun' => $tahun],
                    ['target_value' => $targetVal, 'target_type' => 'persen']
                );

                foreach ($colMap as $bulan => $colIdx) {
                    $nilaiRaw = trim($row[$colIdx] ?? '');
                    $nilai = $this->parseNilai($nilaiRaw);
                    if ($nilai !== null) {
                        IndicatorValue::updateOrCreate(
                            ['indicator_id' => $indicator->id, 'tahun' => $tahun, 'bulan' => $bulan],
                            ['nilai' => $nilai, 'numerator' => null, 'denominator' => null]
                        );
                    }
                }

                $counter++;
            }

            $this->command->line("  [$jenis] $tahun: $counter indikator diproses");
        }
    }

    /**
     * Parse string target Excel menjadi float.
     * Contoh: "≥ 85%", "≤5%", "100%", "> 80%", "0%" -> float
     */
    private function parseTarget(string $raw): ?float
    {
        if (empty($raw) || $raw === '-') {
            return null;
        }

        // Hapus semua karakter non-angka kecuali titik dan koma
        $cleaned = preg_replace('/[^0-9.,]/', '', $raw);
        $cleaned = str_replace(',', '.', $cleaned);

        if (!is_numeric($cleaned)) {
            return null;
        }

        return (float) $cleaned;
    }

    /**
     * Parse nilai bulanan - skip NaN, #DIV/0!, NEW, dan string non-numerik.
     */
    private function parseNilai(string $raw): ?float
    {
        if (empty($raw)
            || $raw === '-'
            || strtolower($raw) === 'nan'
            || str_contains($raw, '#DIV/0!')
            || strtoupper($raw) === 'NEW'
        ) {
            return null;
        }

        // Hapus % jika ada
        $cleaned = str_replace(['%', ' '], '', $raw);
        $cleaned = str_replace(',', '.', $cleaned);

        if (!is_numeric($cleaned)) {
            return null;
        }

        return (float) $cleaned;
    }
}
