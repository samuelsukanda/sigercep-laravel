<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateDesainGrafis extends Command
{
    protected $signature = 'migrasi:desain-grafis';
    protected $description = 'Migrasi data dari data_desain_grafis (database lama) ke desain_grafis (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')->table('data_desain_grafis')->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            DB::connection('mysql')->table('desain_grafis')->insert([
                'nama'      => $row->nama,
                'unit'      => $row->unit,
                'keperluan' => $row->keperluan,
                'tanggal'   => $row->tanggal,
                'desain'    => $row->desain,
                'status'    => $row->status ?: 'pending',
                'panjang'   => $row->width ?: null,
                'tinggi'    => $row->height ?: null,
                'satuan'    => $row->satuan ?: null,
                'menit'     => $row->menit ?: null,
                'detik'     => $row->detik ?: null,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
