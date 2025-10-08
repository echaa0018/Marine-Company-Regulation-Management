<?php

namespace Database\Seeders;

use App\Models\master_data;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $aplications = [
            'VDI',
            'CRM',
            'D-PACTA',
            'NEUTRADC WEBSITE',
            'CORSEC DASH',
            'ONE DASHBOARD',
            'MONTHLY REPORT',
            'NEUTRASAFE',
            'WHOLESALE',
            'CHANNEL',
            'OFFICE 365',
            'ASET',
            'BISPRO',
            'BILCO',
            'TREASURY',
            'PMS',
            'CAPACITY',
            'FINPRO',
            'HCIS',
            'VENDOR MANAGEMENT',
            'PROC',
            'PWC',
            'SAP HANA',
            'Permit to Work ( PTW )',
            'IPORTAL',
        ];

        foreach ($aplications as $i => $aplication) {
            master_data::create([
                            'type' => 'application',
                            'code' => $i+1,
                            'name' => $aplication,
                            ]);
        }

        $devisions = [
            'SALES',
            'PRESALES',
            'PRODUCT',
            'STRATEGIC',
            'DELIVERY',
            'SCM PROC',
            'SCM VENDOR',
            'SCM GA',
            'ITAS',
            'OPERATION',
            'FINANCE',
            'HC',
            'RISK',
            'BISPRO',
            'LEGAL',
            'INTERAL AUDIT',
            'CORCOM',
            'CORSEC',
            'OPERATION',
            'HSE',
            'ASSETS',
        ];

        foreach ($devisions as $i =>$devision) {
            master_data::create([
                'type' => 'devision',
                'code' => $i+1,
                'name' => $devision,
                ]);
        }

        $request_status=[
            'Draft',
            'On Review',
            'Rejected',
            'Approved',
            'Dropped',
            'Done',
        ];

        foreach ($request_status as $v => $stat) {
            master_data::create([
                'type' => 'request_status',
                'code' => $v+1,
                'name' => $stat,
                ]);
        }

        $pa_status=[
            'Non Created',
            'Draft',
            'Section 1 input',
            'Section 2 input',
            'Waiting for Employee Approval',
            'Approved',
            'Done',
        ];

        foreach ($pa_status as $v => $stat) {
            master_data::create([
                'type' => 'pa_status',
                'code' => $v+1,
                'name' => $stat,
                ]);
        }

        $location_type=[
            'Site',
            'Zone',
            'Building',
            'Floor',
            'Room',
        ];

        foreach ($location_type as $v => $loc) {
            master_data::create([
                'type' => 'location_type',
                'code' => $v+1,
                'name' => $loc,
                ]);
        }

        $master_data_prefix=[
            [ 'type' => 'master_data_prefix', 'code' => 'asset_tipe', 'name' => 'AT'],
            [ 'type' => 'master_data_prefix', 'code' => 'brand', 'name' => 'BR'],
            [ 'type' => 'master_data_prefix', 'code' => 'model', 'name' => 'MO'],
            [ 'type' => 'master_data_prefix', 'code' => 'vendor', 'name' => 'VD'],
        ];

        master_data::insert($master_data_prefix);

        
    }
}
