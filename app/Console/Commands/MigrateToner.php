<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateToner extends Command
{
    protected $signature = 'migrasi:toner';
    protected $description = 'Migrasi data toner dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_toner')
            ->get();

        foreach ($old as $row) {

            $defaultSignature = 'storage/signatures/toner/default.png';

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            if (empty($row->tanda_tangan) || $row->tanda_tangan == "?") {
                $tandaTangan = $defaultSignature;
            } else {
                $tandaTangan = 'storage/signatures/toner/' . $row->tanda_tangan;
            }

            DB::connection('mysql')->table('toner')->insert([
                'nama'          => $row->nama,
                'unit'          => $row->unit,
                'toner'         => $row->toner,
                'jumlah'        => $row->jumlah,
                'tanggal'       => $row->tanggal,
                'tanda_tangan'  => $tandaTangan,
                'created_at'    => $createdAt,
                'updated_at'    => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total data: " . count($old));
    }
}
