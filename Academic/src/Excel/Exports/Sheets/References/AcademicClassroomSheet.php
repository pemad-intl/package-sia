<?php

namespace Digipemad\Sia\Academic\Excel\Exports\Sheets\References;

use Digipemad\Sia\Academic\Models\AcademicClassroom;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AcademicClassroomSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
     * Return title of sheet
     */
    public function title(): string
    {
        return 'REF@ROMBEL';
    }

    /*
     * Get data and map()
     */
    public function collection()
    {
        return AcademicClassroom::all();
    }

    /*
     * Set headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'ID Tahun akademik',
            'Tahun akademik',
            'Kelas',
            'Nama rombel',
        ];
    }

    /*
     * Mapping collections
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->semester_id,
            $row->semester->full_name,
            $row->level_id,
            $row->name,
        ];
    }
}