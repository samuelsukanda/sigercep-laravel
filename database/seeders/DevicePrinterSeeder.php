<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class DevicePrinterSeeder extends Seeder
{
    /**
     * Seeder untuk mengisi tabel device_printers dari file Excel
     * File: public/assets/file/Device & Printer.xlsx
     *
     * Struktur Excel (Sheet: Hardware):
     * A: No | B: Nama PC | C: Nama Perangkat | D: Jenis Hardware | E: Merk/Type | F: Lantai | G: IP
     *
     * Catatan: Kolom B (Nama PC) dan G (IP) menggunakan merged cells,
     * sehingga hanya row pertama dalam group yang memiliki nilai.
     * Seeder ini akan carry-forward nilai tersebut ke baris berikutnya yang null.
     */
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

        // Truncate tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('device_printers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Mengisi data device_printers...');

        $currentNamaPC = null;
        $currentIP     = null;
        $inserted      = 0;
        $skipped       = 0;

        // Mulai dari baris 2 (baris 1 adalah header)
        for ($row = 2; $row <= $highestRow; $row++) {
            $no            = $sheet->getCell('A' . $row)->getValue();
            $namaPC        = $sheet->getCell('B' . $row)->getValue();
            $namaPerangkat = $sheet->getCell('C' . $row)->getValue();
            $jenisHardware = $sheet->getCell('D' . $row)->getValue();
            $merkType      = $sheet->getCell('E' . $row)->getValue();
            $lantai        = $sheet->getCell('F' . $row)->getValue();
            $ip            = $sheet->getCell('G' . $row)->getValue();

            // Trim semua nilai string
            $namaPC        = is_string($namaPC) ? trim($namaPC) : $namaPC;
            $namaPerangkat = is_string($namaPerangkat) ? trim($namaPerangkat) : $namaPerangkat;
            $jenisHardware = is_string($jenisHardware) ? trim($jenisHardware) : $jenisHardware;
            $merkType      = is_string($merkType) ? trim($merkType) : $merkType;
            $ip            = is_string($ip) ? trim($ip) : $ip;

            // Skip baris kosong (tidak ada nama perangkat)
            if (empty($namaPerangkat)) {
                $skipped++;
                continue;
            }

            // Carry-forward Nama PC jika null (merged cell)
            if (!empty($namaPC)) {
                $currentNamaPC = $namaPC;
            }

            // Carry-forward IP jika null (merged cell)
            if (!empty($ip)) {
                $currentIP = $ip;
            }

            // ip_pc = hanya IP address
            $ipPc = !empty($currentIP) ? $currentIP : 'Unknown';

            // Keterangan: jika ada info IP printer di kolom H, masukkan ke keterangan
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
