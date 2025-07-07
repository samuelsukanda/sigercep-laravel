<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $users = [
            [
                'name' => 'IT',
                'username' => 'admin',
                'password' => Hash::make('ithmr'),
                'level' => 'superadmin',
            ],
            [
                'name' => 'Hamori',
                'username' => 'hamori',
                'password' => Hash::make('hamori'),
                'level' => 'user',
            ],
            [
                'name' => 'Komite Medik',
                'username' => 'komdik',
                'password' => Hash::make('komdikhmr123'),
                'level' => 'admin',
            ],
            [
                'name' => 'IPSRS',
                'username' => 'ipsrs',
                'password' => Hash::make('ipsrshmr123'),
                'level' => 'admin',
            ],
            [
                'name' => 'SDM',
                'username' => 'sdm',
                'password' => Hash::make('sdm24$'),
                'level' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
