<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateManajemenRisiko extends Command
{
    protected $signature = 'migrasi:manajemen-risiko';
    protected $description = 'Migrasi data dari data_manajemen_risiko (database lama) ke manajemen_risiko (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_manajemen_risiko')
            ->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            DB::connection('mysql')->table('manajemen_risiko')->insert([
                'nama'        => $row->nama,
                'unit'        => $row->unit,
                'tanggal'     => $row->tanggal,
                'uraian'      => $row->uraian,
                'dampak'      => $row->dampak,
                'kemungkinan' => $row->kemungkinan,
                'nilai'       => $row->nilai,
                'keterangan'  => $row->keterangan ?: '-',
                'created_at'  => $createdAt,
                'updated_at'  => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total data yang dipindahkan: " . count($old));
    }
}
