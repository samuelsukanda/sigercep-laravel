<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DaftarRisiko;
use Illuminate\Support\Facades\DB;

class DaftarRisikoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DaftarRisiko::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $filePath = 'public/assets/file/DAFTAR RISIKO RS HAMORI TAHUN 2026.xlsx';

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: $filePath");
            return;
        }

        $this->command->info('Loading spreadsheet...');
        $spreadsheet = IOFactory::load($filePath);

        $sheet = $spreadsheet->getSheet(0);
        $rows = $sheet->toArray();
        $totalRows = count($rows);

        $counter = 0;

        for ($i = 5; $i < $totalRows; $i++) {
            $row = $rows[$i] ?? [];
            $no = trim($row[1] ?? '');

            if (empty($no) || !is_numeric($no) || strpos($no, '.') !== false) {
                continue;
            }

            $unit = trim($row[0] ?? '');
            $risiko = trim($row[2] ?? '');
            
            if (empty($unit) || empty($risiko)) {
                continue;
            }

            DaftarRisiko::create([
                'unit' => $unit,
                'risiko' => $risiko,
                'kode_risiko' => trim($row[4] ?? ''),
                'sebab' => trim($row[5] ?? ''),
                'sumber_risiko' => trim($row[6] ?? ''),
                'c_uc' => trim($row[7] ?? ''),
                'dampak' => trim($row[8] ?? ''),
                'pengendalian' => trim($row[9] ?? ''),
                'efektif' => trim($row[10] ?? '') === '√',
                'tidak_efektif' => trim($row[11] ?? '') === '√',
                'analisis_p' => $this->parseDecimal($row[12] ?? ''),
                'analisis_d' => $this->parseDecimal($row[13] ?? ''),
                'analisis_bobot' => $this->parseDecimal($row[14] ?? ''),
                'analisis_nilai' => $this->parseDecimal($row[15] ?? ''),
                'analisis_tingkat' => trim($row[16] ?? ''),
                'target_waktu' => trim($row[17] ?? ''),
                'mitigasi_p' => $this->parseDecimal($row[18] ?? ''),
                'mitigasi_d' => $this->parseDecimal($row[19] ?? ''),
                'mitigasi_bobot' => $this->parseDecimal($row[20] ?? ''),
                'mitigasi_nilai' => $this->parseDecimal($row[21] ?? ''),
                'mitigasi_tingkat' => trim($row[22] ?? ''),
            ]);

            $counter++;
        }

        $this->command->info("Seeding selesai! Total data: $counter");
    }

    private function parseDecimal($val): ?float
    {
        $val = trim($val);
        if ($val === '') {
            return null;
        }
        
        $val = str_replace(',', '.', $val);
        if (is_numeric($val)) {
            return (float) $val;
        }
        
        return null;
    }
}
