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
                'email' => 'admin@gmail.com',
                'password' => Hash::make('ithmr'),
                'role' => 'superadmin',
            ],
            [
                'name' => 'Hamori',
                'username' => 'hamori',
                'unit_name' => 'hamori',
                'email' => 'hamori@gmail.com',
                'password' => Hash::make('hamori'),
                'role' => 'user',
            ],
            [
                'name' => 'Komite Medik',
                'username' => 'komdik',
                'email' => 'komdik@gmail.com',
                'password' => Hash::make('komdikhmr123'),
                'role' => 'admin',
            ],
            [
                'name' => 'IPSRS',
                'username' => 'ipsrs',
                'email' => 'ipsrs@gmail.com',
                'password' => Hash::make('ipsrshmr123'),
                'role' => 'admin',
            ],
            [
                'name' => 'SDM',
                'username' => 'sdm',
                'email' => 'sdm@gmail.com',
                'password' => Hash::make('sdm24$'),
                'role' => 'admin',
            ],
            [
                'name' => 'MUTU',
                'username' => 'mutu',
                'email' => 'mutu@gmail.com',
                'password' => Hash::make('mutuhmr123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Desain Grafis',
                'username' => 'desaingrafis',
                'email' => 'desaingrafis@gmail.com',
                'password' => Hash::make('hamori24$'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
