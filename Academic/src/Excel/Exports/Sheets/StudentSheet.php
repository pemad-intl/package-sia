<?php

namespace Digipemad\Sia\Academic\Excel\Exports\Sheets;

use Digipemad\Sia\Academic\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
	public $i = 1;

    /**
     * Return title of sheet
     */
    public function title(): string
    {
        return 'DATA@SISWA';
    }

    /*
     * Get data and map()
     */
    public function collection()
    {
        return Student::with('user')->limit(10)->get();
    }

    /*
     * Set headings
     */
    public function headings(): array
    {
        return [
            '#',
            'ID Tahun ajaran',
            'Nama siswa',
            'NIS',
            'NISN',
            'NIK',
            'Tempat lahir',
            'Tanggal lahir',
            'JK',
            'Tanggal masuk',
        ];
    }

    /*
     * Mapping collections
     */
    public function map($student): array
    {
        return [
            $this->i++,
            $student->generation_id,
            $student->full_name,
            $student->nis,
            $student->nisn,
            $student->user->profile->nik,
            $student->user->profile->pob,
            $student->user->profile->dob,
            $student->user->profile->sex,
            $student->entered_at,
        ];
    }
}