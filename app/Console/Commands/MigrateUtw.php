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

        $unitMap = [
            'Admisi' => 'Admisi',
            'AdmisiJKN' => 'Admisi JKN',
            'AhliGizi' => 'Ahli Gizi',
            'Lab' => 'Analis Kesehatan & Laboratorium',
            'Apoteker' => 'Apoteker',
            'Bendahara' => 'Bendahara',
            'Bidan' => 'Bidan',
            'BidanVK' => 'Bidan - VK',
            'Billing' => 'Billing',
            'CallCenter' => 'Call Center',
            'CustomerService' => 'Customer Service',
            'DivAsuhanMutu' => 'Divisi Asuhan & Mutu Keperawatan',
            'DivHLC' => 'Divisi HLC',
            'DivLogistikPengembangan' => 'Divisi Logistik & Pengembangan SDM Keperawatan',
            'Driver' => 'Driver',
            'Fisioterapi' => 'Fisioterapi',
            'GudangFarmasi' => 'Gudang Farmasi',
            'Helper' => 'Helper',
            'JasaMedis' => 'Jasa Medis',
            'KanitAdmisiBilling' => 'Kanit Admisi Billing',
            'KanitLab' => 'Kanit Analis Kesehatan & Laboratorium',
            'KanitGizi' => 'Kanit Gizi',
            'KanitHD' => 'Kanit HD',
            'KanitIGD' => 'Kanit IGD',
            'KanitIntensif' => 'Kanit Intensif',
            'KanitCSSD' => 'Kanit Kamar Operasi & CSSD',
            'KanitPemeliharaan' => 'Kanit Pemeliharaan',
            'KanitPengadaanAset' => 'Kanit Pengadaan & Aset',
            'KanitPoli' => 'Kanit Poliklinik',
            'KanitRadiologi' => 'Kanit Radiologi',
            'KanitRanap' => 'Kanit Rawat Inap',
            'KanitRM' => 'Kanit Rekam Medis',
            'KanitRehabMedik' => 'Kanit Rehabilitasi Medik',
            'KanitVK' => 'Kanit Ruang Bersalin (VK)',
            'KanitTataGraha' => 'Kanit Tata Graha',
            'ManajerCasemix' => 'Manajer Casemix',
            'ManajerKeperawatan' => 'Manajer Keperawatan',
            'ManajerKeuanganAkuntansi' => 'Manajer Keuangan & Akuntansi',
            'ManajerMarketing' => 'Manajer Pemasaran & Layanan Pelanggan',
            'ManajerYanmed' => 'Manajer Pelayanan Medis',
            'ManajerJangmed' => 'Manajer Penunjang Medis',
            'ManajerSDMHukum' => 'Manajer SDM & Hukum',
            'ManajerUmum' => 'Manajer Umum',
            'OperasionalIT' => 'Operasional IT',
            'TeknisiMEP' => 'Operator Teknisi MEP',
            'PenagihanTarif' => 'Penagihan & Tarif',
            'PerawatGigi' => 'Perawat Gigi atau Terapis Gigi',
            'PerawatHD' => 'Perawat HD',
            'PerawatIGD' => 'Perawat IGD',
            'PerawatICU' => 'Perawat Intensif (ICU)',
            'PerawatNicuPicu' => 'Perawat NICU & PICU',
            'PerawatOK' => 'Perawat OK',
            'PerawatPoli' => 'Perawat Poliklinik',
            'PerawatRanap' => 'Perawat Rawat Inap',
            'Radiografer' => 'Radiografer',
            'RefraksiOptical' => 'Refraksi Optical',
            'RM' => 'Rekam Medis',
            'SPVAkuntansi' => 'SPV Akuntansi',
            'SPVFarmasi' => 'SPV Farmasi',
            'SPVKeuangan' => 'SPV Keuangan',
            'SPVLayananPelanggan' => 'SPV Layanan Pelanggan',
            'SPVPelayananMutuJKN' => 'SPV Pelayanan Mutu & JKN',
            'SPVPemasaran' => 'SPV Pemasaran',
            'SPVRanap' => 'SPV Rawat Inap',
            'SPVRajal' => 'SPV Rawat Jalan',
            'SPVSDMHukum' => 'SPV SDM & Hukum',
            'SPVIT' => 'SPV Teknologi Informasi',
            'SPVUmum' => 'SPV Umum',
            'StafBeban' => 'Staf Beban & Hutang Dagang',
            'StafBimroh' => 'Staf Bimroh & Pemulasaran Jenazah',
            'StafDiklat' => 'Staf Diklat',
            'StafElektromedis' => 'Staf Elektromedis',
            'StafHukum' => 'Staf Hukum',
            'StafJKN' => 'Staf JKN',
            'StafPendapatan' => 'Staf Pendapatan, Piutang, Asset & Persediaan',
            'StafPengadaan' => 'Staf Pengadaan',
            'StafPenjualan' => 'Staf Penjualan',
            'StafSDM' => 'Staf SDM',
            'StafVerifikasi' => 'Staf Verifikasi, Anggaran, Pajak',
            'SterilisasiCSSD' => 'Sterilisasi CSSD',
            'TerapisWicara' => 'Terapis Wicara',
            'TeknikFarmasi' => 'Tenaga Teknik Kefarmasian',
            'TransporterRM' => 'Transporter Rekam Medis',
        ];

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
