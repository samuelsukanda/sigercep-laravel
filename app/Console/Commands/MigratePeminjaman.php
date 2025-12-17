<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePeminjaman extends Command
{
    protected $signature = 'migrasi:peminjaman';
    protected $description = 'Migrasi data peminjaman dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_peminjaman')
            ->get();


        foreach ($old as $row) {

            $defaultSignature = 'storage/signatures/peminjaman/default.png';

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            if (empty($row->tanda_tangan) || $row->tanda_tangan == "?") {
                $tandaTangan = $defaultSignature;
            } else {
                $tandaTangan = 'storage/signatures/peminjaman/' . $row->tanda_tangan;
            }

            $status = trim($row->status);

            if ($status == "Sudah Dikembalikan" || $status == "Done") {
                $statusBaru = "Sudah Di Kembalikan";
            } else {
                $statusBaru = "Belum Di Kembalikan";
            }

            DB::connection('mysql')->table('peminjaman')->insert([
                'nama'          => $row->nama,
                'unit'          => $row->unit,
                'tanggal'       => $row->tanggal,
                'barang'        => $row->barang,
                'tanda_tangan'  => $tandaTangan,
                'status'        => $statusBaru,
                'created_at'    => $createdAt,
                'updated_at'    => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total data: " . count($old));
    }
}
