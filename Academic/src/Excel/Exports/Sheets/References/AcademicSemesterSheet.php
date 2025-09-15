<?php

namespace Digipemad\Sia\Academic\Excel\Exports\Sheets\References;

use Digipemad\Sia\Academic\Models\AcademicSemester;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AcademicSemesterSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
     * Return title of sheet
     */
    public function title(): string
    {
        return 'REF@TAHUN_AKADEMIK_SEMESTER';
    }

    /*
     * Get data and map()
     */
    public function collection()
    {
        return AcademicSemester::all();
    }

    /*
     * Set headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tahun akademik',
        ];
    }

    /*
     * Mapping collections
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->full_name,
        ];
    }
}