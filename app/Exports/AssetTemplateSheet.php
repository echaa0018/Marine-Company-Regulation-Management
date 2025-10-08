<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetTemplateSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'Name', 'Type', 'Tag','Old Tag',
            'Brand', 'Model', 'Serial Number', 'SAP Number',
            'Description', 'BPO Number','Contract Number',
            'Vendor', 'Location', 'Loc Description',
        ];
    }

    public function array(): array
    {
        return [
            ['CONTOH PAC', 'PAC', 'TDE4A2200019', 'SCC1201601271', 
            'LIEBERT', 'P2100DA13SHL', '21F01126722158010004', '\'130100001305', 
            'PAC LIEBERT P2100DA13SHL', '', '', '-', 'SRPZ00B01F01R01', 'Serpong ruangan server'],
        ];
    }

    public function title(): string
    {
        return 'Asset';
    }

    public function styles(Worksheet $sheet)
    {
        // Warna merah untuk header kolom yang required
        return [
            'A1:N1' => ['font' => ['bold' => true]],
            'A1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
            'B1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
            'C1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
            'G1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
            'H1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
            'I1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
            'M1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
            'N1' => ['font' => ['color' => ['rgb' => 'FF0000']]],
        ];
    }
}
