<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateSuratKeputusan extends Command
{

    protected $signature = 'migrasi:surat-keputusan';
    protected $description = 'Migrasi data dari database lama ke database baru Laravel';

    public function handle()
    {
        $old = DB::connection('mysql_old')
            ->table('data_sk')
            ->get();

        $unitMap = [
            'MOD'                   => 'Penunjukan Petugas MOD',
            'KoordinatorMOD'        => 'Penunjukan Koordinator MOD',
            'P2K3'                  => 'Penunjukan Pengurus P2K3',
            'AhliK3'                => 'Penunjukan Ahli K3',
            'DokterP2K3'            => 'Kementerian Penunjukan Dokter P2K3',
            'KementerianAhliK3'     => 'Kementerian Ahli K3',
            'JKN'                   => 'Pembentukan PIC Layanan JKN',
            'LayananJKN'            => 'Penunjukan PIC Portal Informasi Faskes Layanan JKN',
            'PPPPK'                 => 'Penunjukan Pejabat Pengadaan (PP) dan Pejabat Pembuat Komitmen (PPK)',
            'DokterAnestesi'        => 'Dokter PJ Anestesi IBS, CSSD, dan Rawat Intensif',
            'DokterLab'             => 'Dokter PJ Unit Laboratorium',
            'DokterRadiologi'       => 'Dokter PJ Unit Radiologi',
            'CIKep'                 => 'Penunjukan Clinical Instructor (CI) Keperawatan',
            'SPGDT'                 => 'Penunjukan SPGDT (Sistem Penanggulangan Gawat Darurat Terpadu)',
            'Marketing'             => 'Manajer Pemasaran dan Layanan Pelanggan',
            'YanMed'                => 'Manajer Pelayanan Medis',
            'AktKeu'                => 'Manajer Keuangan dan Akuntansi',
            'Umum'                  => 'Manajer Umum',
            'Casemix'               => 'Manajer Casemix',
            'JangMed'               => 'PJS Manajer Penunjang Medis',
            'Keperawatan'           => 'PJS Manajer Keperawatan',
            'SDMHukum'              => 'SPV SDM dan Hukum',
            'Keuangan'              => 'SPV Keuangan',
            'Akuntansi'             => 'SPV Akuntansi',
            'SPVUmum'               => 'SPV Umum',
            'Penjualan'             => 'SPV Penjualan',
            'LayananPelanggan'      => 'SPV Layanan Pelanggan',
            'Rajal'                 => 'SPV Rawat Jalan',
            'SPVRanap'              => 'SPV Rawat Inap',
            'Farmasi'               => 'SPV Farmasi',
            'IT'                    => 'SPV IT',
            'PelMutuJKN'            => 'SPV Pelayanan dan Mutu JKN',
            'LogistikPengembangan'  => 'PJS SPV Logistik dan Pengembangan SDM Keperawatan',
            'Poli'                  => 'Kanit Poli',
            'OK'                    => 'Kanit OK',
            'Gizi'                  => 'Kanit Gizi',
            'TataGraha'             => 'Kanit Tata Graha',
            'PengadaanAset'         => 'Kanit Pengadaan dan Aset',
            'Ranap'                 => 'Kanit Rawat Inap',
            'HD'                    => 'Kanit HD',
            'AdmisiBilling'         => 'Kanit Admisi & Billing',
            'Pemeliharaan'          => 'Kanit Pemeliharaan',
            'Laboratorium'          => 'Kanit Laboratorium',
            'Radiologi'             => 'Kanit Radiologi',
            'RM'                    => 'Kanit Rekam Medis',
            'IGD'                   => 'Kanit IGD',
            'RehabMedik'            => 'PJS Kanit Rehabilitasi Medik',
            'SKTunjangan'           => 'Tunjangan Di Luar Jam Kerja',
            'SKSanksi'              => 'Sanksi Disiplin Pelanggaran Karyawan',
            'SKKetentuan'           => 'Ketentuan Jam Besuk Rawat Inap',
        ];

        foreach ($old as $row) {

            $createdAt = !empty($row->waktu_input)
                ? $row->waktu_input
                : now();
            $filePdf = basename($row->file_path);
            $newPath = 'surat-keputusan/' . $filePdf;
            $unitBaru = $unitMap[$row->unit] ?? $row->unit;

            DB::connection('mysql')->table('surat_keputusan')->insert([
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
