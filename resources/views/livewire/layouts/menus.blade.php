<div>
    {{-- Menu --}}
    <x-menu class="text-xs space-y-0 p-1" active-bg-color="font-black" activate-by-route>
        {{-- Home --}}
        <x-menu-item title="Home" icon="o-home" link="{{ route('home') }}" />
        {{-- Documents Dropdown --}}
        <x-menu-sub title="Documents" icon="o-document" link="#">
          <x-menu-item title="Document Catalog" link="{{ route('document.catalog') }}" />
          <x-menu-item title="Create Document" link="{{ route('document.create') }}" />
        </x-menu-sub>
        {{-- @can('admin-control') --}}
          <x-menu-sub title="Administrator" icon="o-cog-6-tooth" link="#">
            <x-menu-item title="Users" icon="o-users" link="{{ route('user.index') }}" />
            <x-menu-item title="Roles" icon="o-user-group" link="{{ route('role.index') }}" />
          </x-menu-sub>
        {{-- @endcan --}}
      </x-menu>
</div>
