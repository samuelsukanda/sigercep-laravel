<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            [
                'name' => 'pupu.pujiawati',
                'username' => 'pupu.pujiawati@rs-hamori.co.id',
                'email' => 'pupu.pujiawati@rs-hamori.co.id',
                // 'password' => bcrypt('password'),
                'nik' => '011010',
                'unit' => 'Mutu',
                'jabatan' => 'Ketua Mutu',
            ],
            [
                'name' => 'rifaldi.zakhari',
                'username' => 'rifaldi.zakhari@rs-hamori.co.id',
                'email' => 'rifaldi.zakhari@rs-hamori.co.id',
                // 'password' => bcrypt('password'),
                'nik' => '011011',
                'unit' => 'SDM',
                'jabatan' => 'Staf Diklat dan Pengembangan',
            ],
            [
                'name' => 'meliana.fatimah',
                'username' => 'meliana.fatimah@rs-hamori.co.id',
                'email' => 'meliana.fatimah@rs-hamori.co.id',
                // 'password' => bcrypt('password'),
                'nik' => '011012',
                'unit' => 'Komite Medik',
                'jabatan' => 'Staf Komite Medik',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
