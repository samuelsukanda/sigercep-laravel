<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\MasterKomputer;

class MasterKomputerSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('assets/file/Hardware.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: $filePath");
            return;
        }

        $this->command->info('Membaca file Hardware.xlsx...');
        $spreadsheet = IOFactory::load($filePath);
        $worksheet   = $spreadsheet->getActiveSheet();
        $rows        = $worksheet->toArray();

        MasterKomputer::truncate();

        $inserted = 0;
        $skipped  = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            $ip = trim($row[5] ?? '');

            if (empty($ip)) {
                $skipped++;
                continue;
            }

            MasterKomputer::updateOrCreate(
                ['ip' => $ip],
                [
                    'nama_pc'  => trim($row[1] ?? ''),
                    'jenis_pc' => trim($row[2] ?? ''),
                    'unit'     => trim($row[3] ?? ''),
                    'lantai'   => trim($row[4] ?? ''),
                    'ram'      => trim($row[6] ?? ''),
                    'cpu'      => trim($row[7] ?? ''),
                ]
            );

            $inserted++;
        }

        $this->command->info('Seeding master_komputer selesai!');
        $this->command->info("  - Data berhasil dimasukkan : $inserted baris");
        $this->command->info("  - Baris dilewati (IP kosong): $skipped baris");
        $this->command->info("  - Total di DB              : " . MasterKomputer::count() . " record");
    }
}
