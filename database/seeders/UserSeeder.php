<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        // Superadmin
        $superAdmin = User::create([
            'name' => 'IT',
            'username' => 'admin',
            'password' => Hash::make('ithmr'),
            'level' => 'superadmin',
        ]);
        $superAdmin->assignRole('Superadmin');

        // Admin
        $komdik = User::create([
            'name' => 'Komite Medik',
            'username' => 'komdik',
            'password' => Hash::make('komdikhmr123'),
            'level' => 'admin',
        ]);
        $komdik->assignRole('Admin');
        $komdik->givePermissionTo([
            'update komite-medik',
            'delete komite-medik',
        ]);

        $ipsrs = User::create([
            'name' => 'IPSRS',
            'username' => 'ipsrs',
            'password' => Hash::make('ipsrshmr123'),
            'level' => 'admin',
        ]);
        $ipsrs->assignRole('Admin');
        $ipsrs->givePermissionTo([
            'update ipsrs',
            'delete ipsrs',
            'update outsourcing-vendor',
            'delete outsourcing-vendor',
            'update kesehatan-lingkungan',
            'delete kesehatan-lingkungan',
        ]);

        $sdm = User::create([
            'name' => 'SDM',
            'username' => 'sdm',
            'password' => Hash::make('sdm24$'),
            'level' => 'admin',
        ]);
        $sdm->assignRole('Admin');
        $sdm->givePermissionTo([
            'update utw',
            'delete utw',
            'update peraturan-perusahaan',
            'delete peraturan-perusahaan',
            'update surat-keputusan',
            'delete surat-keputusan',
            'update mandatory-training',
            'delete mandatory-training',
        ]);

        $mutu = User::create([
            'name' => 'MUTU',
            'username' => 'mutu',
            'password' => Hash::make('mutuhmr123'),
            'level' => 'admin',
        ]);
        $mutu->assignRole('Admin');
        $mutu->givePermissionTo([
            'update mutu',
            'delete mutu',
            'update bank-spo',
            'delete bank-spo',
            'update manajemen-risiko',
            'delete manajemen-risiko',
        ]);

        $dg = User::create([
            'name' => 'Desain Grafis',
            'username' => 'desaingrafis',
            'password' => Hash::make('hamori24$'),
            'level' => 'admin',
        ]);
        $dg->assignRole('Admin');
        $dg->givePermissionTo([
            'update desain-grafis',
            'delete desain-grafis',
        ]);

        // User
        $user = User::create([
            'name' => 'Hamori',
            'username' => 'hamori',
            'password' => Hash::make('hamori'),
            'level' => 'user',
        ]);
        $user->assignRole('User');
    }
}
