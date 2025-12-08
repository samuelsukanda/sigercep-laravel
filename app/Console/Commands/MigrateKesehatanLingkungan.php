<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateKesehatanLingkungan extends Command
{
    protected $signature = 'migrasi:kesehatan-lingkungan';
    protected $description = 'Migrasi data dari tabel kesehatan_lingkungan (database lama) ke kesehatan_lingkungan (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')->table('data_kesehatan_lingkungan')->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            $fotoPath = null;
            if (!empty($row->dokumentasi)) {
                $fotoPath = "images/kesehatan-lingkungan/" . $row->dokumentasi;
            }

            DB::connection('mysql')->table('kesehatan_lingkungan')->insert([
                'nama'             => $row->nama,
                'unit'             => $row->unit,
                'tanggal'          => $row->tanggal,
                'lokasi_masalah'   => $row->lokasi_masalah,
                'jenis_hama'       => $row->jenis_hama,
                'dokumentasi'      => $fotoPath,
                'created_at'       => $createdAt,
                'updated_at'       => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
