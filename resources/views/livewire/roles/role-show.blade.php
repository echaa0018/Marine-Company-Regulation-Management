<div>
    {{-- The Master doesn't talk, he acts. --}}
    <x-card class="mb-5">
        

        <x-form wire:submit="update">
            <x-header title="Role : {{ $role->name }}" size="text-xl" separator/>
        
            {{-- <x-header title="Permission:" size="text-xl" separator /> --}}
        
            @php $tmp = ''; @endphp
        
            @foreach ($permission as $v)
                @php
                    $permissionName = explode('-', $v->name);
                    $group = $permissionName[0];
                @endphp
        
                @if ($group != $tmp)
                    <label class="custom-control-label mt-1 block text-base font-bold text-base-700">
                        {{ ucwords($group) }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-2">
                @endif
        
                <x-checkbox  
                    label="{{ $v->name }}" 
                    {{-- wire:model="permissionsSelected"  --}}
                    wire:model.defer="permissionsSelected" 
                    value="{{ $v->name }}" 
                    :checked="in_array($v->id, $rolePermissions)" 
                    class="text-xs"
                />
        
                @php $nextGroup = $loop->remaining > 0 
                    ? explode('-', $permission[$loop->index + 1]->name)[0] 
                    : null; 
                @endphp
        
                @if ($nextGroup != $group)
                    </div>
                @endif
        
                @php $tmp = $group; @endphp
            @endforeach
        
            <x-slot:actions>
                <x-button label="Back" class="btn-sm" onclick="window.history.back()" />
                <x-button label="Update" class="btn-primary btn-sm" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
