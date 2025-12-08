<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateReservasiKendaraan extends Command
{
    protected $signature = 'migrasi:reservasi-kendaraan';
    protected $description = 'Migrasi data dari data_reservasi_kendaraan (database lama) ke reservasi_kendaraan (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')->table('data_reservasi_kendaraan')->get();

        foreach ($old as $row) {

            DB::connection('mysql')->table('reservasi_kendaraan')->insert([
                'nama'             => $row->nama,
                'unit'             => $row->unit,
                'tempat_tujuan'    => $row->tempat_tujuan,
                'keperluan'        => $row->keperluan,
                'jam_berangkat'    => $row->jam_berangkat,
                'jam_pulang'       => $row->jam_pulang,
                'tanggal'          => $row->tanggal,
                'jenis_kendaraan'  => $row->jenis_kendaraan,
                'jumlah_penumpang' => $row->jumlah_penumpang ?: '-',
                'waktu_tempuh'     => $row->waktu_tempuh ?: '-',
                'jarak_tempuh'     => $row->jarak_tempuh ?: '-',
                'jenis_layanan'    => $row->jenis_layanan,
                'created_at'       => $row->waktu_input,
                'updated_at'       => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
