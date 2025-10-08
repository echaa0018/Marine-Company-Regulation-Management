<?php

namespace Database\Seeders;

use App\Models\Aplication;
use App\Models\Devision;
use App\Models\Status_config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'proposal-list',
            'proposal-create',
            'proposal-edit',
            'proposal-delete',
            'user-control',
            'admin-control',
            'asset-control'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        
    }
}
