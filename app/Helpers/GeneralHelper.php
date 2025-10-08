<?php

use App\Models\location;
use App\Models\master_data;

if (!function_exists('getInitials')) {
    function getInitials($name) {
        $words = explode(' ', trim($name));
        if (count($words) == 1) {
            return strtoupper(substr($words[0], 0, 1));
        }

        $firstInitial = strtoupper(substr($words[0], 0, 1));
        $lastInitial = strtoupper(substr(end($words), 0, 1));

        return $firstInitial . $lastInitial;
    }
}

if (!function_exists('setRowNumber')) {
    function setRowNumber($paginatedData)
    {
        $paginatedData->getCollection()->transform(function ($item, $index) use ($paginatedData) {
            $item->row_number = ($paginatedData->currentPage() - 1) * $paginatedData->perPage() + $index + 1;
            return $item;
        });

        return $paginatedData;
    }
}

if (!function_exists('generateMasterDataCode')) {
    function generateMasterDataCode($tipe)
    {
        $lastcode = DB::select("SELECT nextval('master_data_seq') as number")[0]->number;
        $data = master_data::select('name')
            ->where('type', 'master_data_prefix')
            ->where('code', $tipe)
            ->first();

        $pad = 6;

        return $data ? $data->name . str_pad($lastcode, $pad, '0', STR_PAD_LEFT) : null;    
    }
}

if (!function_exists('getMasterDataCodeById')) {
    function getMasterDataCodeById($id, $default = null): ?string
    {
        if (empty($id)) {
            return null;
        }
        return master_data::where('id', $id)->value('code') ?? $default;
    }
}

if (!function_exists('getGeneralLocation')) {
    function getGeneralLocation(): ?array
    {
        $locations = location::select('id','type', 'code', 'parent_code', 'name')
            ->get()
            ->groupBy('type');

        // Buat index berdasarkan code untuk akses cepat
        $locationIndex = Location::pluck('name', 'code')->toArray();
        $parentIndex = Location::pluck('parent_code', 'code')->toArray();

        $data = [];

        foreach ($locations[5] as $room) {
            $path = [$room->name];
            $parent = $parentIndex[$room->code] ?? null;

            // Cari parent berulang sampai ketemu site
            while ($parent) {
                $path[] = $locationIndex[$parent] ?? '';
                $parent = $parentIndex[$parent] ?? null;
            }

            $data[] = [
                'id' => $room->id,
                'code' => $room->code,
                'name' => implode(' > ', array_reverse($path))
            ];
        }

        return $data;
    }
}

if (!function_exists('getLocationName')) {
    function getLocationName(string $code): ?string
    {
        // Ambil semua lokasi untuk membentuk indeks
        $locationIndex = Location::pluck('name', 'code')->toArray();
        $parentIndex   = Location::pluck('parent_code', 'code')->toArray();

        if (!isset($locationIndex[$code])) {
            return null; // Jika kode tidak ditemukan
        }

        // Mulai dari code yang diberikan
        $path = [$locationIndex[$code]];
        $parent = $parentIndex[$code] ?? null;

        // Naik ke parent hingga habis
        while ($parent) {
            $path[] = $locationIndex[$parent] ?? '';
            $parent = $parentIndex[$parent] ?? null;
        }

        // Balik urutan agar Site > Zone > ... > Room
        return implode(' > ', array_reverse($path));
    }
}