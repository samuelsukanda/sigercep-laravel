<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateReservasiRuangan extends Command
{
    protected $signature = 'migrasi:reservasi-ruangan';
    protected $description = 'Migrasi data dari reservasi_ruangan (database lama) ke reservasi_ruangan (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')->table('data_reservasi_ruangan')->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            DB::connection('mysql')->table('reservasi_ruangan')->insert([
                'nama'       => $row->nama,
                'unit'       => $row->unit,
                'jam_mulai'  => $row->jam_mulai,
                'jam_selesai' => $row->jam_selesai,
                'tanggal'    => $row->tanggal,
                'ruang'      => $row->ruang,
                'approval'   => $row->approval ?: 'pending',
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
