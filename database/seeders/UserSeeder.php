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
                'role' => 'superadmin',
            ],
            [
                'name' => 'Hamori',
                'username' => 'hamori',
                'password' => Hash::make('hamori'),
                'role' => 'user',
            ],
            [
                'name' => 'Komite Medik',
                'username' => 'komdik',
                'password' => Hash::make('komdikhmr123'),
                'role' => 'admin',
            ],
            [
                'name' => 'IPSRS',
                'username' => 'ipsrs',
                'password' => Hash::make('ipsrshmr123'),
                'role' => 'admin',
            ],
            [
                'name' => 'SDM',
                'username' => 'sdm',
                'password' => Hash::make('sdm24$'),
                'role' => 'admin',
            ],
            [
                'name' => 'MUTU',
                'username' => 'mutu',
                'password' => Hash::make('mutuhmr123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Desain Grafis',
                'username' => 'desaingrafis',
                'password' => Hash::make('hamori24$'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
