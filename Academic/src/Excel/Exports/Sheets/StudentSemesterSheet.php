<?php

namespace Digipemad\Sia\Academic\Excel\Exports\Sheets;

use Digipemad\Sia\Academic\Models\StudentSemester;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentSemesterSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
	public $i = 1;

    /**
     * Return title of sheet
     */
    public function title(): string
    {
        return 'DATA@SEMESTER_SISWA';
    }

    /*
     * Get data and map()
     */
    public function collection()
    {
        return StudentSemester::with('student', 'classroom.semester')->limit(10)->get();
    }

    /*
     * Set headings
     */
    public function headings(): array
    {
        return [
            '#',
            'ID Siswa',
            'ID Rombel',
        ];
    }

    /*
     * Mapping collections
     */
    public function map($semester): array
    {
        return [
            $this->i++,
            $semester->student_id,
            $semester->classroom_id,
        ];
    }
}