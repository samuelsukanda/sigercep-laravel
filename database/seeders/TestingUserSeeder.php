<?php

namespace Database\Seeders;

use App\Models\ApprovalMapping;
use App\Models\ChangeRequest;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestingUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'            => 'Test Supervisor Akuntansi',
                'username'        => 'req.akuntansi@rs-hamori.co.id',
                'unit'            => 'Akuntansi',
                'unit_id'         => 9001,
                'jabatan'         => 'Supervisor Akuntansi',
                'jabatan_id'      => 9001,
                'nik'             => '91001',
            ],
            [
                'name'            => 'Test SPV Akuntansi (HRIS-style)',
                'username'        => 'spv.akuntansi@rs-hamori.co.id',
                'unit'            => 'Akuntansi',
                'unit_id'         => 9001,
                'jabatan'         => 'SPV Akuntansi',
                'jabatan_id'      => 9001,
                'nik'             => '91002',
            ],
            [
                'name'            => 'Test Manajer Keuangan',
                'username'        => 'appr.keuangan@rs-hamori.co.id',
                'unit'            => 'Akuntansi',
                'unit_id'         => 9001,
                'jabatan'         => 'Manajer Keuangan dan Akuntansi',
                'jabatan_id'      => 9002,
                'nik'             => '91003',
            ],
            [
                'name'            => 'Test Manajer Umum',
                'username'        => 'mum@rs-hamori.co.id',
                'unit'            => 'Umum',
                'unit_id'         => 9002,
                'jabatan'         => 'Manajer Umum',
                'jabatan_id'      => 9003,
                'nik'             => '91004',
            ],
            [
                'name'            => 'Test IT',
                'username'        => 'it.test@rs-hamori.co.id',
                'unit'            => 'Teknologi dan Informasi',
                'unit_id'         => 9003,
                'jabatan'         => 'Operasional IT Technical Support',
                'jabatan_id'      => 9004,
                'nik'             => '91005',
            ],
        ];

        $emails = collect($users)->pluck('username');

        // Bersihkan sisa run sebelumnya
        $testUserIds = User::whereIn('username', $emails)->pluck('id');
        ChangeRequest::whereIn('user_id', $testUserIds)->delete();
        DB::table('notifications')->whereIn('notifiable_id', $testUserIds)->delete();

        // Master jabatan (id dari HRIS / buatan untuk testing)
        Jabatan::upsert([
            ['id' => 9001, 'nama' => 'Supervisor Akuntansi',      'manager_id' => 9002, 'level_approve' => 2],
            ['id' => 9002, 'nama' => 'Manajer Keuangan dan Akuntansi', 'manager_id' => 9003, 'level_approve' => 3],
            ['id' => 9003, 'nama' => 'Manajer Umum',              'manager_id' => null, 'level_approve' => 4],
            ['id' => 9004, 'nama' => 'Operasional IT Technical Support', 'manager_id' => 9003, 'level_approve' => 1],
        ], ['id'], ['nama', 'manager_id', 'level_approve']);

        // User tes
        foreach ($users as $u) {
            User::updateOrCreate(['username' => $u['username']], $u + ['email' => $u['username'], 'status_karyawan' => 'active']);
        }

        // Link mapping: atasan langsung peminta
        ApprovalMapping::where('requester_jabatan', 'Supervisor Akuntansi')
            ->update(['requester_jabatan_id' => 9001]);
        ApprovalMapping::where('approver_jabatan', 'Manajer Keuangan dan Akuntansi')
            ->update(['approver_jabatan_id' => 9002]);

        $this->command->info('5 user testing berhasil di-seed.');
    }
}