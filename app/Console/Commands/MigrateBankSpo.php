<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateBankSpo extends Command
{

    protected $signature = 'migrasi:bank-spo';
    protected $description = 'Migrasi data dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_bank_spo')
            ->get();

        $unitMap = [
            'Asuhan Mutu'            => 'Asuhan Mutu',
            'Casemix'                => 'Dept. Casemix',
            'Dept Yanmed'            => 'Dept. Pelayanan Medis',
            'Dept Jangmed'           => 'Dept. Penunjang Medis',
            'Dialisis'               => 'Dialisis',
            'Akuntansi'              => 'Div. Akuntansi',
            'HLC'                    => 'Div. HLC',
            'Dept SDM & Hukum'       => 'Dept. SDM & Hukum',
            'Keuangan'               => 'Div. Keuangan',
            'Teknologi Informasi'    => 'Div. Teknologi Informasi',
            'Duty Manager'           => 'Duty Manajer',
            'Farmasi'                => 'Farmasi',
            'Gizi'                   => 'Gizi',
            'IGD'                    => 'IGD',
            'Intensif'               => 'Intensif',
            'CSSD'                   => 'Kamar Operasi & CSSD',
            'Komite Hukum'           => 'Komite Etik & Hukum',
            'Komite Farmasi'         => 'Komite Farmasi Terapi',
            'Komite K3RS'            => 'Komite K3RS',
            'Komite Keperawatan'     => 'Komite Keperawatan',
            'Komite Medik'           => 'Komite Medik',
            'Komite Mutu'            => 'Komite Mutu',
            'Komite Nakes'           => 'Komite Nakes Lain',
            'Komite PPRA'            => 'Komite PPRA',
            'Komite PPI'             => 'Komite PPI',
            'Laboratorium'           => 'Laboratorium',
            'Layanan Pelanggan'      => 'Layanan Pelanggan',
            'Logistik Pengembangan'  => 'Logistik & Pengembangan',
            'Pemasaran'              => 'Pemasaran',
            'Pemeliharaan'           => 'Pemeliharaan',
            'Pengadaan & Aset'       => 'Pengadaan & Aset',
            'Poliklinik'             => 'Poliklinik',
            'Radiologi'              => 'Radiologi',
            'RawatInap'              => 'Rawat Inap',
            'Rehabilitasi Medik'     => 'Rehabilitasi Medik',
            'Rekam Medik'            => 'Rekam Medik',
            'Ruang Bersalin'         => 'Ruang Bersalin',
            'Tata Graha'             => 'Tata Graha',
        ];

        foreach ($old as $row) {

            $createdAt = !empty($row->waktu_input)
                ? $row->waktu_input
                : now();
            $filePdf = basename($row->file_path);
            $newPath = 'bank-spo/' . $filePdf;
            $unitBaru = $unitMap[$row->unit] ?? $row->unit;

            DB::connection('mysql')->table('bank_spo')->insert([
                'file_pdf'   => $filePdf,
                'file_path'  => $newPath,
                'unit'       => $unitBaru,
                'jenis_spo'  => $row->jenis_spo,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
