<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateUtw extends Command
{

    protected $signature = 'migrasi:utw';
    protected $description = 'Migrasi data dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_utw')
            ->get();

        foreach ($old as $row) {

            $createdAt = !empty($row->waktu_input)
                ? $row->waktu_input
                : now();
            $filePdf = basename($row->file_path);
            $newPath = 'utw/' . $filePdf;
            $unitBaru = $unitMap[$row->unit] ?? $row->unit;

            DB::connection('mysql')->table('utw')->insert([
                'file_pdf'   => $filePdf,
                'file_path'  => $newPath,
                'unit'       => $unitBaru,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
