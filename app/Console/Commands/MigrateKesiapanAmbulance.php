<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateKesiapanAmbulance extends Command
{
    protected $signature = 'migrasi:kesiapan-ambulance';
    protected $description = 'Migrasi data kesiapan ambulance dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_kesiapan_ambulance')
            ->get();

        foreach ($old as $row) {

            $tanggal = ($row->tanggal == "0000-00-00" || empty($row->tanggal))
                ? now()->toDateString()
                : $row->tanggal;

            if (!empty($row->tanggal) && $row->tanggal != "0000-00-00") {
                $createdAt = $row->tanggal . ' 07:00:00';
            } else {
                $createdAt = now();
            }

            DB::connection('mysql')->table('kesiapan_ambulance')->insert([
                'mobil_ambulance'   => $row->mobil_ambulance,
                'tanggal'           => $tanggal,
                'perawat'           => $row->perawat,
                'kondisi_mobil'     => $row->kondisi_mobil,
                'kondisi_driver'    => $row->kondisi_driver,
                'oksigen'           => $row->oksigen,
                'regulator_oksigen' => $row->regulator_oksigen,
                'kebersihan'        => $row->kebersihan,
                'monitor_pasien'    => $row->monitor_pasien,
                'aed'               => $row->aed,
                'suction'           => $row->suction,
                'ventilator'        => $row->ventilator,
                'bed_pasien'        => $row->bed_pasien,
                'linen'             => $row->linen,
                'obat'              => $row->obat,
                'inverter'          => $row->inverter,
                'created_at'        => $createdAt,
                'updated_at'        => now(),
            ]);
        }

        $this->info("Migrasi selesai! Total: " . count($old));
    }
}
