<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PreviewSheetHandler implements ToCollection
{
    public $rows;

    public function collection(Collection $collection)
    {
        $this->rows = $collection;
    }
}

class PreviewAssetImport implements WithMultipleSheets
{
    public $sheetHandler;

    public function __construct()
    {
        $this->sheetHandler = new PreviewSheetHandler();
    }

    public function sheets(): array
    {
        return [
            0 => $this->sheetHandler, // Sheet kedua
        ];
    }
}
