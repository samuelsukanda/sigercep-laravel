<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateKomiteMedik extends Command
{
    protected $signature = 'migrasi:komite-medik';
    protected $description = 'Migrasi data dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_komite_medik')
            ->get();

        $unitMap = [
            // PPK
            'PPKAnak'            => 'PPK - KSM Anak',
            'PPKAnestesi'        => 'PPK - KSM Anestesi',
            'PPKBedah'           => 'PPK - KSM Bedah',
            'PPKGigi'            => 'PPK - KSM Gigi',
            'PPKJantung'         => 'PPK - KSM Jantung',
            'PPKKulit'           => 'PPK - KSM Kulit',
            'PPKObgyn'           => 'PPK - KSM Obgyn',
            'PPKParu'            => 'PPK - KSM Paru',
            'PPKPenunjang'       => 'PPK - KSM Penunjang',
            'PPKPenyakitDalam'   => 'PPK - KSM Penyakit Dalam',
            'PPKSaraf'           => 'PPK - KSM Saraf',

            // Clinical Pathway
            'CPAnak'             => 'Clinical Pathway - KSM Anak',
            'CPAnestesi'         => 'Clinical Pathway - KSM Anestesi',
            'CPBedah'            => 'Clinical Pathway - KSM Bedah',
            'CPGigi'             => 'Clinical Pathway - KSM Gigi',
            'CPJantung'          => 'Clinical Pathway - KSM Jantung',
            'CPKulit'            => 'Clinical Pathway - KSM Kulit',
            'CPObgyn'            => 'Clinical Pathway - KSM Obgyn',
            'CPParu'             => 'Clinical Pathway - KSM Paru',
            'CPPenunjang'        => 'Clinical Pathway - KSM Penunjang',
            'CPPenyakitDalam'    => 'Clinical Pathway - KSM Penyakit Dalam',
            'CPSaraf'            => 'Clinical Pathway - KSM Saraf',
        ];

        foreach ($old as $row) {

            $createdAt = !empty($row->waktu_input)
                ? $row->waktu_input
                : now();
            $filePdf = basename($row->file_path);
            $newPath = 'komite-medik/' . $filePdf;
            $unitBaru = $unitMap[$row->unit] ?? $row->unit;

            DB::connection('mysql')->table('komite_medik')->insert([
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
