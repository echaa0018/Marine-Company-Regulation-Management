
<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

    <x-card >
        <div class="flex justify-end mb-4">
            {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
            <x-button label="Add" icon="o-plus" @click="$wire.createForm = true" class="btn-primary btn-sm " />
        </div>
        
        <x-hr target="gotoPage" />
        <x-table
            class="text-xs"
            :headers="$t_headers"
            :rows="$roles"
            with-pagination
            {{-- per-page="[1]" --}}
            {{-- :per-page-values="[1]"  --}}
        >
             {{-- Special `actions` slot --}}
            @scope('cell_action', $role)
                <div class="flex gap-1">
                    <x-button icon="o-eye" tooltip="Role Permission" onclick="window.location.href='{{ route('role.show', $role->id) }}'" spinner class="btn-xs" />
                    <x-button icon="o-trash" tooltip="Delete" wire:click="showDeleteModal({{ $role->id }})" spinner class="btn-xs" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Role" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-input label="Name" icon="o-user" wire:model="name" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}
         
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save"  />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteModal" title="Are you sure?" subtitle="delete this role?">
        <x-form no-separator>
     
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
                <x-button label="Confirm" class="btn-primary" @click="$wire.deleteRole({{ $selectedRoleId }})" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
    
</div>
