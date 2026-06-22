<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportHardware extends Command
{
    protected $signature = 'import:hardware
                            {file? : Path ke file Excel (relative public/assets/file atau absolut)}
                            {--tanggal= : Tanggal pemeriksaan (format Y-m-d, default hari ini)}';
    protected $description = 'Import data hardware dari Excel ke database sigercep';

    public function handle()
    {
        $fileInput = $this->argument('file');

        if (blank($fileInput)) {
            $this->error('Masukkan path file Excel, contoh: php artisan import:hardware Hardware.xlsx --tanggal=2026-06-22');
            return 1;
        }

        $path = $this->resolvePath($fileInput);

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: $path");
            return 1;
        }

        $tanggal = $this->option('tanggal') ?: now()->toDateString();

        $spreadsheet = null;
        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Throwable $e) {
            $this->error('Gagal membaca file Excel: '.$e->getMessage());
            return 1;
        }

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $total = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue; // skip header
            }

            $ip      = $row[4] ?? trim(($row[3] ?? ''));
            $nama    = $row[1] ?? '';
            $unit    = $row[2] ?? '';
            $lantai  = $row[3] ?? '';

            if (empty($ip)) {
                $skipped++;
                continue;
            }

            $checklist = $this->defaultChecklist();

            \App\Models\Hardware::create([
                'ip'       => (string) $ip,
                'nama'     => (string) $nama,
                'unit'     => (string) $unit,
                'lantai'   => (string) $lantai,
                'tanggal'  => $tanggal,
                'checklist'=> json_encode($checklist, JSON_UNESCAPED_UNICODE),
            ]);

            $total++;
        }

        $this->info("Import selesai. Total ditambahkan: $total | Dilewati: $skipped");
        return 0;
    }

    private function resolvePath(string $fileInput): string
    {
        $file = ltrim($fileInput, '/\\');

        if (preg_match('#^[A-Za-z]:[\\\\/]#', $file)) {
            return $file;
        }

        $candidate = public_path('assets/file/'.$file);
        if (file_exists($candidate)) {
            return $candidate;
        }

        $candidate = public_path($file);
        if (file_exists($candidate)) {
            return $candidate;
        }

        return base_path($file);
    }

    private function defaultChecklist(): array
    {
        return [
            'Wallpaper backround RS' => ['status' => '', 'keterangan' => null],
            'Pastikan login sistem operasi ada dua (admin dan limit)' => ['status' => '', 'keterangan' => null],
            'Pastikan password admin dan limit terkontrol' => ['status' => '', 'keterangan' => null],
            'Screen saver jalan' => ['status' => '', 'keterangan' => null],
            'Aplikasi remote VNC berjalan' => ['status' => '', 'keterangan' => null],
            'Folder sharing tersedia' => ['status' => '', 'keterangan' => null],
            'Bersihkan komputer dari software yang tidak diijinkan' => ['status' => '', 'keterangan' => null],
            'Cek kapasitas hardisk sistem operasi C' => ['status' => '', 'keterangan' => null],
            'Jalankan SIMRS HAMORI' => ['status' => '', 'keterangan' => null],
            'IP address sesuai' => ['status' => '', 'keterangan' => null],
            'Ping Local dan Internet berjalan/reply' => ['status' => '', 'keterangan' => null],
            'Printer bisa digunakan' => ['status' => '', 'keterangan' => null],
            'Catridge terisi tinta' => ['status' => '', 'keterangan' => null],
            'Cek nyalanya Monitor' => ['status' => '', 'keterangan' => null],
            'Cek fungsi UPS' => ['status' => '', 'keterangan' => null],
            'Cek fungsi Mouse' => ['status' => '', 'keterangan' => null],
            'Cek fungsi Keyboard' => ['status' => '', 'keterangan' => null],
            'Bersihkan Casing bagian dalam dari debu' => ['status' => '', 'keterangan' => null],
            'Rapihkan pengkabelan' => ['status' => '', 'keterangan' => null],
            'Rapikan penempatan' => ['status' => '', 'keterangan' => null],
        ];
    }
}
