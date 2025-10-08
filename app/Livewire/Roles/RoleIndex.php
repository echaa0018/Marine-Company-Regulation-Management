<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    use WithPagination;
    use Toast;
    // public int $perPage = 3;

    public $selectedRoleId;
    public bool $createForm = false;
    
    public bool $deleteModal = false;
    public $name, $role ;


    public function showDeleteModal($roleId)
    {
        // dd($userId);
        $this->selectedRoleId = $roleId;
        $this->deleteModal = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->role = '';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);


        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        

        $this->resetForm();
        $this->toast(
            type: 'success',
            title: 'Data Saved',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: route('role.index')                    // optional (uri)
        );
        $this->showForm = false;
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            $this->toast(
                type: 'success',
                title: 'Role Deleted',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-info',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
                redirectTo: route('role.index')                    // optional (uri)
            );
        }
    }

    public function goToShow($id)
    {
        return redirect()->route('role.show', ['id' => $id]);
    }

    public function render()
    {
        $title = 'Role Management';
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
        
        $roles = Role::orderBy('id','DESC')->paginate(10);

        $roles->getCollection()->transform(function ($role, $index) use ($roles) {
            $role->row_number = ($roles->currentPage() - 1) * $roles->perPage() + $index + 1;
            return $role;
        });

        // dd($users);
 
        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            // ['key' => 'email', 'label' => 'Email'],
            // ['key' => 'roles.0.name', 'label' => 'Role'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-2'],
        ];
        return view('livewire.roles.role-index' , [
            't_headers' => $t_headers,
            'roles'=> $roles,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
