<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateKomplainIpsrs extends Command
{
    protected $signature = 'migrasi:komplain-ipsrs';
    protected $description = 'Migrasi data dari data_komplain_ipsrs (database lama) ke komplain_ipsrs (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')->table('data_komplain_ipsrs')->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            $fotoPath = null;
            if (!empty($row->foto)) {
                $fotoPath = "images/komplain-ipsrs/" . $row->foto;
            }

            DB::connection('mysql')->table('komplain_ipsrs')->insert([
                'nama'        => $row->nama,
                'unit'        => $row->unit,
                'tujuan_unit' => $row->tujuan_unit,
                'tanggal'     => $row->tanggal,
                'kendala'     => $row->kendala,
                'foto'        => $fotoPath,
                'status'      => $row->status,
                'keterangan'  => $row->keterangan,
                'created_at'  => $createdAt,
                'updated_at'  => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
