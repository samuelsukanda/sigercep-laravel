<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePengembalianAset extends Command
{
    protected $signature = 'migrasi:pengembalian-aset';
    protected $description = 'Migrasi data dari data_pengembalian_aset (database lama) ke pengembalian_aset (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_pengembalian_aset')
            ->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            $fotoBarang = null;
            if (!empty($row->foto)) {
                $fotoBarang = "images/pengembalian-aset/foto-barang/" . $row->foto;
            }

            $fotoBarcode = null;
            if (!empty($row->foto_barcode)) {
                $fotoBarcode = "images/pengembalian-aset/foto-barcode/" . $row->foto_barcode;
            }

            DB::connection('mysql')->table('pengembalian_aset')->insert([
                'nama'               => $row->nama,
                'unit'               => $row->unit,
                'keperluan'          => $row->keperluan,
                'tanggal'            => $row->tanggal,
                'nama_barang'        => $row->nama_barang,
                'tempat_asal_barang' => $row->tempat_asal,
                'foto_barang'        => $fotoBarang,
                'foto_barcode'       => $fotoBarcode ?: null,
                'created_at'         => $createdAt,
                'updated_at'         => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total data: " . count($old));
    }
}
