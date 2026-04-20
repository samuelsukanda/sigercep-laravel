<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class DokumenITPermissionSeeder extends Seeder
{
    public function run()
    {
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($actions as $action) {

            $permission = Permission::where('menu', 'dokumen_it')
                ->where('action', $action)
                ->first();

            if ($permission) {
                $permission->update([
                    'menu' => 'dokumen_it',
                    'action' => $action
                ]);
            }
            else {
                $permission = Permission::create([
                    'menu' => 'dokumen_it',
                    'action' => $action
                ]);
            }

            $permission->rules()->delete();

            $permission->rules()->create([
                'unit' => 'teknologi dan informasi'
            ]);
        }
    }
}
