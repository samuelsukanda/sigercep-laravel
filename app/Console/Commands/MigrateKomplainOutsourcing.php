<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateKomplainOutsourcing extends Command
{
    protected $signature = 'migrasi:komplain-outsourcing-vendor';
    protected $description = 'Migrasi data dari data_komplain_outsourcing_vendor (database lama) ke komplain_outsourcing_vendor (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')->table('data_komplain_outsourcing_vendor')->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            $fotoPath = null;
            if (!empty($row->foto)) {
                $fotoPath = "images/komplain-outsourcing-vendor/" . $row->foto;
            }

            DB::connection('mysql')->table('komplain_outsourcing_vendor')->insert([
                'nama'        => $row->nama,
                'unit'        => $row->unit,
                'tujuan_unit' => $row->tujuan_unit,
                'jam'         => $row->jam,
                'tanggal'     => $row->tanggal,
                'kendala'     => $row->kendala,
                'area'        => $row->area,
                'foto'        => $fotoPath,
                'created_at'  => $createdAt,
                'updated_at'  => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
