<?php

namespace Digipemad\Sia\Academic\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentSemesterExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * Return sheets
     */
    public function sheets(): array
    {
        return [
            new Sheets\StudentSemesterSheet(),
            new Sheets\References\AcademicClassroomSheet(),
            new Sheets\References\StudentSheet(),
        ];
    }
}