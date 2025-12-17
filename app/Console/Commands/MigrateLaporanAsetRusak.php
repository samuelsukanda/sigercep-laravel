<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLaporanAsetRusak extends Command
{
    protected $signature = 'migrasi:laporan-aset-rusak';
    protected $description = 'Migrasi data dari data_laporan_aset_rusak (database lama) ke laporan_aset_rusak (Laravel)';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_laporan_aset_rusak')
            ->get();

        foreach ($old as $row) {

            $createdAt = $row->tanggal . ' ' . $row->waktu_input;

            $foto = null;
            if (!empty($row->foto)) {
                $foto = "images/laporan-aset-rusak/foto-barang/" . $row->foto;
            }

            $fotoBarcode = null;
            if (!empty($row->foto_barcode)) {
                $fotoBarcode = "images/laporan-aset-rusak/foto-barcode/" . $row->foto_barcode;
            }

            $statusLama = trim($row->status);

            if (
                strcasecmp($statusLama, 'Bisa Di perbaiki') === 0 ||
                strcasecmp($statusLama, 'Bisa di perbaiki') === 0 ||
                strcasecmp($statusLama, 'Bisa diperbaiki') === 0 ||
                strcasecmp($statusLama, 'Bisa diPerbaiki') === 0
            ) {
                $statusBaru = 'Bisa Diperbaiki';
            } elseif (strcasecmp($statusLama, 'Rusak Total') === 0) {
                $statusBaru = 'Rusak Total';
            } else {
                $statusBaru = 'Bisa Diperbaiki';
            }

            DB::connection('mysql')->table('laporan_aset_rusak')->insert([
                'nama'          => $row->nama,
                'unit'          => $row->unit,
                'nama_aset'     => $row->nama_aset,
                'lokasi_aset'   => $row->lokasi_aset,
                'kondisi_aset'  => $row->kondisi_aset,
                'tanggal'       => $row->tanggal,
                'status'        => $statusBaru,
                'foto'          => $foto,
                'foto_barcode'  => $fotoBarcode,
                'created_at'    => $createdAt,
                'updated_at'    => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total data: " . count($old));
    }
}
