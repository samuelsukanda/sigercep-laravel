<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class DevicePrinterSeeder extends Seeder
{
    
    public function run(): void
    {
        $filePath = base_path('public/assets/file/Device & Printer.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: $filePath");
            return;
        }

        $this->command->info('Membaca file Excel...');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName('Hardware');

        if (!$sheet) {
            $this->command->error('Sheet "Hardware" tidak ditemukan!');
            return;
        }

        $highestRow = $sheet->getHighestRow();
        $this->command->info("Total baris Excel: $highestRow");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('device_printers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Mengisi data device_printers...');

        $currentNamaPC = null;
        $currentIP     = null;
        $inserted      = 0;
        $skipped       = 0;

        for ($row = 2; $row <= $highestRow; $row++) {
            $no            = $sheet->getCell('A' . $row)->getValue();
            $namaPC        = $sheet->getCell('B' . $row)->getValue();
            $namaPerangkat = $sheet->getCell('C' . $row)->getValue();
            $jenisHardware = $sheet->getCell('D' . $row)->getValue();
            $merkType      = $sheet->getCell('E' . $row)->getValue();
            $lantai        = $sheet->getCell('F' . $row)->getValue();
            $ip            = $sheet->getCell('G' . $row)->getValue();

            $namaPC        = is_string($namaPC) ? trim($namaPC) : $namaPC;
            $namaPerangkat = is_string($namaPerangkat) ? trim($namaPerangkat) : $namaPerangkat;
            $jenisHardware = is_string($jenisHardware) ? trim($jenisHardware) : $jenisHardware;
            $merkType      = is_string($merkType) ? trim($merkType) : $merkType;
            $ip            = is_string($ip) ? trim($ip) : $ip;

            if (empty($namaPerangkat)) {
                $skipped++;
                continue;
            }
            if (!empty($namaPC)) {
                $currentNamaPC = $namaPC;
            }

            if (!empty($ip)) {
                $currentIP = $ip;
            }
            $ipPc = !empty($currentIP) ? $currentIP : 'Unknown';

            $keterangan = null;
            $extraInfo  = $sheet->getCell('H' . $row)->getValue();
            if (!empty($extraInfo)) {
                $keterangan = trim($extraInfo);
            }

            DB::table('device_printers')->insert([
                'ip_pc'          => $ipPc,
                'nama_perangkat' => $namaPerangkat,
                'jenis'          => $jenisHardware ?? 'Lainnya',
                'merk_type'      => !empty($merkType) ? $merkType : null,
                'kondisi'        => 'Baik',
                'keterangan'     => $keterangan,
                'foto'           => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $inserted++;
        }

        $this->command->info('Seeding selesai!');
        $this->command->info("  - Data berhasil dimasukkan : $inserted baris");
        $this->command->info("  - Baris dilewati (kosong)  : $skipped baris");
        $this->command->info("  - Total di DB              : " . DB::table('device_printers')->count() . " record");
    }
}
