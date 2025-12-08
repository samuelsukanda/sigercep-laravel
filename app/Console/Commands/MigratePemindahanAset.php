<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePemindahanAset extends Command
{
    protected $signature = 'migrasi:pemindahan-aset';
    protected $description = 'Migrasi data dari data_pemindahan_aset (database lama) ke pemindahan_aset (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_pemindahan_aset')
            ->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            $fotoBarang = null;
            if (!empty($row->foto)) {
                $fotoBarang = "images/pemindahan-aset/foto-barang/" . $row->foto;
            }

            $fotoBarcode = null;
            if (!empty($row->foto_barcode)) {
                $fotoBarcode = "images/pemindahan-aset/foto-barcode/" . $row->foto_barcode;
            }

            DB::connection('mysql')->table('pemindahan_aset')->insert([
                'nama'               => $row->nama,
                'unit_asal'          => $row->unit_asal,
                'unit_tujuan'        => $row->unit_tujuan,
                'keperluan'          => $row->keperluan,
                'tanggal'            => $row->tanggal,
                'nama_barang'        => $row->nama_barang,
                'foto_barang'        => $fotoBarang,
                'foto_barcode'       => $fotoBarcode ?: null,
                'created_at'         => $createdAt,
                'updated_at'         => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total data: " . count($old));
    }
}
