<?php

namespace Database\Seeders;

use App\Models\ApprovalMapping;
use Illuminate\Database\Seeder;

class ApprovalMappingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Manajer Casemix
            ['Supervisor Pelayanan dan Mutu JKN (PJS)', 'Manajer Casemix'],
            ['Supervisor Administrasi Klaim JKN (PJS)', 'Manajer Casemix'],
            // Manajer Penunjang Medik
            ['Supervisor SDM dan Logistik Penunjang', 'Manajer Penunjang Medik'],
            ['Kepala Instalasi Farmasi', 'Manajer Penunjang Medik'],
            ['Kepala Instalasi Rekam Medik', 'Manajer Penunjang Medik'],
            ['Kepala Instalasi Gizi', 'Manajer Penunjang Medik'],
            ['Kepala Instalasi Laboratorium', 'Manajer Penunjang Medik'],
            ['Kepala Instalasi Rehabilitasi Medik (PJS)', 'Manajer Penunjang Medik'],
            ['Kepala Instalasi Radiologi', 'Manajer Penunjang Medik'],
            // Manajer Pelayanan Medik
            ['Supervisor SDM dan Logistik Medik', 'Manajer Pelayanan Medik'],
            ['Supervisor Mutu Layanan dan Disiplin Medik', 'Manajer Pelayanan Medik'],
            ['Kepala Instalasi Gawat Darurat', 'Manajer Pelayanan Medik'],
            ['Kepala Instalasi Dialisis', 'Manajer Pelayanan Medik'],
            ['Kepala Instalasi Rawat Jalan', 'Manajer Pelayanan Medik'],
            ['Kepala Instalasi Kamar Bersalin', 'Manajer Pelayanan Medik'],
            ['Kepala Instalasi Kamar Operasi & CSSD', 'Manajer Pelayanan Medik'],
            ['Kepala Instalasi Rawat Intensive (PJS)', 'Manajer Pelayanan Medik'],
            ['Kepala Instalasi Rawat Inap', 'Manajer Pelayanan Medik'],
            // Manajer Keperawatan
            ['Supervisor Asuhan dan Mutu Keperawatan dan Kebidanan', 'Manajer Keperawatan'],
            // Manajer Pemasaran dan Layanan Pelanggan
            ['Supervisor Pemasaran', 'Manajer Pemasaran dan Layanan Pelanggan'],
            ['Supervisor Penjualan', 'Manajer Pemasaran dan Layanan Pelanggan'],
            ['Supervisor Layanan Pelanggan', 'Manajer Pemasaran dan Layanan Pelanggan'],
            ['Koordinator Admisi', 'Manajer Pemasaran dan Layanan Pelanggan'],
            // Manajer Umum
            ['Supervisor Pengadaan Asset dan Tata Grha', 'Manajer Umum'],
            ['Supervisor Pemeliharaan', 'Manajer Umum'],
            ['Supervisor IT', 'Manajer Umum'],
            // Manajer Keuangan dan Akuntansi
            ['Supervisor Akuntansi', 'Manajer Keuangan dan Akuntansi'],
            ['Supervisor Keuangan', 'Manajer Keuangan dan Akuntansi'],
            // Manajer SDM dan Hukum
            ['Supervisor SDM dan Hukum', 'Manajer SDM dan Hukum'],
            // Tanpa atasan langsung -> langsung ke Manajer Umum
            ['Ketua Komite Mutu', 'Manajer Umum'],
            ['PPI', 'Manajer Umum'],
        ];

        ApprovalMapping::query()->truncate();

        foreach ($data as [$requester, $approver]) {
            ApprovalMapping::create([
                'requester_jabatan' => $requester,
                'approver_jabatan'  => $approver,
            ]);
        }

        $this->command->info(count($data) . ' mapping atasan langsung berhasil di-seed.');
    }
}
