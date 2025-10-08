<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
  </head>
  <body class="min-h-screen font-sans antialiased bg-base-200">
    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
      <x-slot:brand>
        <x-app-brand />
      </x-slot:brand>
      <x-slot:actions>
        <label for="main-drawer" class="lg:hidden me-3">
          <x-icon name="o-bars-3" class="cursor-pointer" />
        </label>
      </x-slot:actions>
    </x-nav>

   

    {{-- MAIN --}}
    <x-main  full-width>
      {{-- <x-hr /> --}}

      
      
      {{-- SIDEBAR --}}
      <x-slot:sidebar drawer="main-drawer" class="bg-base-100 p-0 w-62 space-y-4" >


        {{-- Logo --}}
        {{-- <a href="{{ route('home') }}" class="flex justify-center">
          <img src="{{ asset('image/NEUTRA-logo-2.png') }}" alt="Logo" class="h-10">
        </a> --}}


        <div class="display-when-collapsed hidden  mt-5  text-right  w-full">

          <button class="btn btn-xs btn-ghost mb-1 w-full" @click="toggle">
            <x-icon name="o-chevron-double-right" />
          </button>

          <x-menu-separator />
        </div>
      

        <div class="flex items-center justify-between  ">

          <x-app-brand class="mt-2 ml-2" />
          <button class="mary-hideable btn btn-ghost btn-xs mt-3" @click="toggle">
            <x-icon name="o-chevron-double-left" />
          </button>

        </div>

        

        {{-- User --}}
        @if($user = auth()->user())
          <x-menu-separator/>

          <div class="mary-hideable ">
            <div class="flex items-center justify-between ">
              {{-- Bagian kiri: Avatar --}}
              <div class="pl-2 w-2/10"> {{-- Fixed width --}}
                  <x-avatar 
                      placeholder="{{ getInitials($user->name) }}" 
                      {{-- title="{{ $user->name }}" 
                      subtitle="{{ $user->email }}"  --}}
                      class="!w-10 pt-0" 
                  />
              </div>

              <div class="w-7/10 pl-2 text-left truncate">
                {{-- Bagian tengah: Nama Pengguna --}}
                {{-- <x-header title="{{ $user->name }}" subtitle="{{ $user->email }}" size="text-sm" class="!mb-0 pl-2 w-32" /> --}}
                <div class="text-md font-extrabold ">{{ \Illuminate\Support\Str::limit($user->name.'', 20) }}</div>
                <div class="text-xs text-base-500">{{ \Illuminate\Support\Str::limit($user->email.'', 24) }}</div>
              </div>

              {{-- Bagian tengah: Nama Pengguna --}}
              {{-- Bagian kanan: Dropdown --}}
              <div class="w-2/10 text-right">
                  <x-dropdown>
                      <x-slot:trigger>
                          <button class="btn btn-xs btn-ghost mb-1">
                              <x-icon name="o-cog" />
                          </button>
                      </x-slot:trigger>

                      <x-menu-item title="Theme" icon="o-swatch" @click="$dispatch('mary-toggle-theme')" />
                      <x-menu-item title="Logout" link="{{ route('logout') }}" icon="o-arrow-left-start-on-rectangle" />
                  </x-dropdown>
              </div>

              {{-- Theme toggle (hidden) --}}
              <x-theme-toggle class="hidden" />


            </div>
          </div>
          
          <div class="flex cursor-pointer  justify-center " @click="toggle">
            <div class="display-when-collapsed hidden">
              <x-avatar placeholder="{{ getInitials($user->name) }}"  class=" !w-10 " />
            </div>
          </div>

          
          <x-menu-separator />

        @endif

        {{-- <div class="flex   text-right p-1  w-full">
          <button class="mary-hideable btn ml-1.5 btn-xs btn-ghost " @click="toggle">
            <x-icon name="o-chevron-double-left" />
          </button>


          <button class="display-when-collapsed hidden btn btn-xs btn-ghost w-full" @click="toggle">
            <x-icon name="o-chevron-double-right" />
          </button>
        </div>

        <x-menu-separator /> --}}

        {{-- Menu --}}
        {{-- <x-menu class="text-xs space-y-0 p-1" activate-by-route>
          <x-menu-item title="Home" icon="o-home" link="{{ route('home') }}" />
          <x-menu-item title="Messages" icon="o-envelope" link="#" />
          <x-menu-sub title="Settings" icon="o-cog-6-tooth" link="#">
            <x-menu-item title="Wifi" icon="o-wifi" link="#" />
            <x-menu-item title="Archives" icon="o-archive-box" link="#" />
          </x-menu-sub>
        </x-menu> --}}

        @livewire('layouts.menus', ['title' => $title])

      </x-slot:sidebar>


      {{-- The `$slot` goes here --}}
      <x-slot:content class="!p-0 bg-base-100/50 " style="background-image: url('/image/dotnoise-light-grey.png');">
        {{-- <div style="bg-[url('/image/brushed-alum-dark.png')] bg-cover bg-center bg-no-repeat"> --}}
          <x-header title="{{ $title }}" class="!mb-4.5 pt-4 pl-3 " separator />

          <x-breadcrumbs :items="$breadcrumbs" class="ml-5 mb-5" />

          <div class="pl-3 pr-3 pt-2 pb-4">
              {{ $slot }}
          </div>
        {{-- </div> --}}
      </x-slot:content>
    </x-main>

    {{-- TOAST area --}}
    <x-toast />
  </body>
</html>
