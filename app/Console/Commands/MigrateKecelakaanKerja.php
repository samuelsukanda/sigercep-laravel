<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateKecelakaanKerja extends Command
{
    protected $signature = 'migrasi:kecelakaan-kerja';
    protected $description = 'Migrasi data dari data_kecelakaan_kerja (database lama) ke kecelakaan_kerja (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_kecelakaan_kerja')
            ->get();

        foreach ($old as $row) {

            $defaultSignature = 'storage/signatures/toner/default.png';

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            if (empty($row->tanda_tangan) || $row->tanda_tangan == "?") {
                $tandaTangan = $defaultSignature;
            } else {
                $tandaTangan = 'storage/signatures/toner/' . $row->tanda_tangan;
            }

            DB::connection('mysql')->table('kecelakaan_kerja')->insert([
                'nama'               => $row->nama,
                'unit'               => $row->unit,
                'no_hp'              => $row->no_hp,
                'jam'                => $row->jam,
                'tanggal'            => $row->tanggal,
                'jenis_kecelakaan'   => $row->jenis_kecelakaan,
                'lokasi_kecelakaan'  => $row->lokasi_kecelakaan,
                'saksi'              => $row->saksi ?: '-',
                'kegiatan'           => $row->kegiatan,
                'riwayat'            => $row->riwayat,
                'penyebab'           => $row->penyebab,
                'bahan'              => $row->bahan ?: '-',
                'cedera'             => $row->cedera,
                'pengobatan'         => $row->pengobatan,
                'pengobatan2'        => $row->pengobatan2,
                'pelaksana'          => $row->pelaksana,
                'tanda_tangan'       => $tandaTangan,
                'created_at'         => $createdAt,
                'updated_at'         => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total data: " . count($old));
    }
}
