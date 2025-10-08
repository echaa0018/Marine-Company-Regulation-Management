<?php

namespace App\Livewire\Roles;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleShow extends Component
{
    use WithPagination;
    use Toast;
    // public int $perPage = 3;


    public $role;
    public $role_id;
    public array $permissionsSelected = [];

    // Akan dipanggil otomatis saat komponen diakses via route (karena pakai parameter route)
    public function mount($id)
    {
        $this->role_id = $id;
        $this->role = Role::findOrFail($id);
        $this->permissionsSelected = $this->role->permissions->pluck('name')->toArray();
    }

    public function update()
    {
        $this->validate([
            'role.name' => 'required|string',
            'permissionsSelected' => 'required|array',
        ]);

        // $this->role->save();
        $this->role->syncPermissions($this->permissionsSelected);

        $this->toast(
            type: 'success',
            title: 'Role Updated',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: route('role.index')                    // optional (uri)
        );

        // return redirect()->route('role.index');
    }

    public function render()
    {
        $title = 'Role Permission';
        $breadcrumbs = [
            [
                'link' => route("home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("user.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Admin', 
            ],
            [
                'link' => route("role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Role', 
            ],
        ];
        
        $role = Role::find($this->role_id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$this->role_id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


 
        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            // ['key' => 'email', 'label' => 'Email'],
            // ['key' => 'roles.0.name', 'label' => 'Role'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-2'],
        ];
        return view('livewire.roles.role-show' , [
            't_headers' => $t_headers,
            'role'=> $role,
            'permission'=> $permission,
            'rolePermissions'=> $rolePermissions,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
