<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePeraturanPerusahaan extends Command
{

    protected $signature = 'migrasi:peraturan-perusahaan';
    protected $description = 'Migrasi data dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_peraturan_perusahaan')
            ->get();

        foreach ($old as $row) {

            $createdAt = !empty($row->waktu_input)
                ? $row->waktu_input
                : now();
            $filePdf = basename($row->file_path);
            $newPath = 'peraturan-perusahaan/' . $filePdf;

            DB::connection('mysql')->table('peraturan_perusahaan')->insert([
                'file_pdf'   => $filePdf,
                'file_path'  => $newPath,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
