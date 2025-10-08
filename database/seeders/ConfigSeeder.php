<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::create([
            'name' => 'Admin Admin',
            'ad_name' => 'admin.admin',
            'phone' => '0812345678',
            'nik' => '2101001',
            'title' => 'Administrator',
            'department' => 'Enterprise IT System & Automation',
            'email' => 'admin.admin@neutradc.com',
            'password' => bcrypt('password')
        ]);

        $role = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Employee']);
        $role3 = Role::create(['name' => 'Admin HC']);
        

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);
        // $role2->syncPermissions($permissions);
        $role3->syncPermissions(['asset-control']);

        $user->assignRole([$role->id]);

        $user2 = User::create([
            'name' => 'Admin Admin2',
            'ad_name' => 'admin.admin2',
            'phone' => '0812345678',
            'nik' => '2101002',
            'title' => 'Administrator2',
            'department' => 'Enterprise IT System & Automation',
            'email' => 'admin.admin2@neutradc.com',
            'password' => bcrypt('password')
        ]);

        $user2->assignRole([$role2->id]);

        $user3 = User::create([
            'name' => 'Houshou Marine',
            'ad_name' => 'houshou.marine',
            'phone' => '0812345679',
            'nik' => '2101003',
            'title' => 'Administrator',
            'department' => 'Enterprise IT System & Automation',
            'email' => 'marine@ahoy.com',
            'password' => bcrypt('110819Marine')
        ]);

        $user3->assignRole([$role->id]);
        

    }
}
