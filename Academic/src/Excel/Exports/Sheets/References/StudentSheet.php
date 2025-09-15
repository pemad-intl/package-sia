<?php

namespace Digipemad\Sia\Academic\Excel\Exports\Sheets\References;

use Digipemad\Sia\Academic\Models\Student;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
     * Return title of sheet
     */
    public function title(): string
    {
        return 'REF@SISWA';
    }

    /*
     * Get data and map()
     */
    public function collection()
    {
        return Student::with('user')->whereNull('graduated_at')->get();
    }

    /*
     * Set headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'NIS',
            'Nama siswa',
            'Jenis kelamin',
        ];
    }

    /*
     * Mapping collections
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->nis,
            $row->full_name,
            $row->user->profile->sex_name,
        ];
    }
}