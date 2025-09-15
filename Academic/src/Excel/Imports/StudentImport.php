<?php

namespace Digipemad\Sia\Academic\Excel\Imports;

use Digipemad\Sia\Academic\Excel\Imports\Sheets\StudentSheet;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class StudentImport implements WithMultipleSheets, SkipsUnknownSheets
{
    use Importable;

    /**
     * Just sheet 1
     */
    public function sheets(): array
    {
        return [
            0 => new StudentSheet(),
        ];
    }
    
    /*
     * If sheet is unknown
     */
    public function onUnknownSheet($sheetName) {}
}