<?php

namespace Digipemad\Sia\Academic\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * Return sheets
     */
    public function sheets(): array
    {
        return [
            new Sheets\StudentSheet(),
            new Sheets\References\AcademicSheet(),
        ];
    }
}