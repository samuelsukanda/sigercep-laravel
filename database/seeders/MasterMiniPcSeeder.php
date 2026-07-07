<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\MasterMiniPc;

class MasterMiniPcSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('assets/file/Mini PC.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: $filePath");
            return;
        }

        $this->command->info('Membaca file Mini PC.xlsx...');
        $spreadsheet = IOFactory::load($filePath);
        $worksheet   = $spreadsheet->getActiveSheet();
        $rows        = $worksheet->toArray();

        MasterMiniPc::truncate();

        $inserted = 0;
        $skipped  = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            // IP di Mini PC.xlsx ada di kolom index 4, ada kemungkinan pakai koma sebagai desimal
            $ip = trim(str_replace(',', '.', $row[4] ?? ''));

            if (empty($ip)) {
                $skipped++;
                continue;
            }

            MasterMiniPc::updateOrCreate(
                ['ip' => $ip],
                [
                    'nama_pc'  => trim($row[1] ?? ''),
                    'jenis_pc' => trim($row[2] ?? ''),
                    'lantai'   => trim($row[3] ?? ''),
                    'ram'      => trim($row[5] ?? ''),
                    'cpu'      => trim($row[6] ?? ''),
                ]
            );

            $inserted++;
        }

        $this->command->info('Seeding master_mini_pc selesai!');
        $this->command->info("  - Data berhasil dimasukkan : $inserted baris");
        $this->command->info("  - Baris dilewati (IP kosong): $skipped baris");
        $this->command->info("  - Total di DB              : " . MasterMiniPc::count() . " record");
    }
}
