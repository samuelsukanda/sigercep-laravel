<?php

namespace Database\Seeders;

use App\Models\ApprovalMapping;
use App\Models\ChangeRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestingUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'            => 'Test SPV Akuntansi',
                'username'        => 'spv.akuntansi@rs-hamori.co.id',
                'unit'            => 'Akuntansi',
                'unit_id'         => 9001,
                'jabatan'         => 'SPV Akuntansi',
                'jabatan_id'      => 9001,
                'nik'             => '91001',
            ],
            [
                'name'            => 'Test Manajer Keuangan',
                'username'        => 'appr.keuangan@rs-hamori.co.id',
                'unit'            => 'Akuntansi',
                'unit_id'         => 9001,
                'jabatan'         => 'Manajer Keuangan dan Akuntansi',
                'jabatan_id'      => 9002,
                'nik'             => '91002',
            ],
            [
                'name'            => 'Test SPV Casemix',
                'username'        => 'spv.casemix@rs-hamori.co.id',
                'unit'            => 'casemix',
                'unit_id'         => 9004,
                'jabatan'         => 'SPV Casemix',
                'jabatan_id'      => 9005,
                'nik'             => '91003',
            ],
              [
                'name'            => 'Test Manajer Casemix',
                'username'        => 'appr.casemix@rs-hamori.co.id',
                'unit'            => 'casemix',
                'unit_id'         => 9004,
                'jabatan'         => 'Manajer Casemix',
                'jabatan_id'      => 9006,
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

        // User tes
        foreach ($users as $u) {
            User::updateOrCreate(['username' => $u['username']], $u + ['email' => $u['username'], 'status_karyawan' => 'active']);
        }

        // Link mapping: atasan langsung peminta (per-user + id jabatan)
        ApprovalMapping::where('requester_jabatan', 'Supervisor Akuntansi')
            ->update(['requester_jabatan_id' => 9001, 'requester_user_id' => User::where('username', 'req.akuntansi@rs-hamori.co.id')->value('id')]);
        ApprovalMapping::where('approver_jabatan', 'Manajer Keuangan dan Akuntansi')
            ->update(['approver_jabatan_id' => 9002, 'approver_user_id' => User::where('username', 'appr.keuangan@rs-hamori.co.id')->value('id')]);

        // Tahap 2: user Manajer Umum terpilih
        Setting::set('stage2_user_id', User::where('username', 'mum@rs-hamori.co.id')->value('id'));

        $this->command->info('5 user testing berhasil di-seed.');
    }
}