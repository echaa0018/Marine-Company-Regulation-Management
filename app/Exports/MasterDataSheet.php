<?php

namespace App\Exports;

use App\Models\master_data;
use DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MasterDataSheet implements FromArray, WithTitle, WithEvents
{
    protected $dataByType = [];

    public function __construct()
    {
        $this->dataByType = master_data::whereIn('type', ['vendor', 'asset_type', 'brand', 'model'])
            ->orderBy('type', 'ASC')
            ->get()
            ->groupBy('type');
    }

    public function array(): array
    {
        // kosong, kita isi manual di AfterSheet
        return [[]];
    }

    public function title(): string
    {
        return 'Master Data';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $columnOffset = 1; // kolom mulai dari A
                foreach ($this->dataByType as $type => $rows) {
                    // Header
                    $sheet->setCellValueByColumnAndRow($columnOffset, 1, strtoupper($type));
                    $sheet->setCellValueByColumnAndRow($columnOffset, 2, 'No');
                    $sheet->setCellValueByColumnAndRow($columnOffset + 1, 2, 'Code');
                    $sheet->setCellValueByColumnAndRow($columnOffset + 2, 2, 'Name');

                    $rowOffset = 3;
                    foreach ($rows as $index => $row) {
                        $sheet->setCellValueByColumnAndRow($columnOffset, $rowOffset, $index + 1);
                        $sheet->setCellValueByColumnAndRow($columnOffset + 1, $rowOffset, $row->code);
                        $sheet->setCellValueByColumnAndRow($columnOffset + 2, $rowOffset, $row->name);
                        $rowOffset++;
                    }

                    // Pindah ke kolom berikutnya setelah 1 blok (3 kolom + 1 spasi)
                    $columnOffset += 4;
                }
            }
        ];
    }
}
