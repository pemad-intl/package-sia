<?php

namespace Digipemad\Sia\Academic\Excel\Imports\Sheets;

use Digipemad\Sia\Academic\Models\Academic;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Illuminate\Support\Collection;

class StudentSemesterSheet implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    /**
     * On each row
     */
    public function collection(Collection $rows)
    {
        $classrooms = AcademicClassroom::find($rows->pluck('id_rombel'));

        foreach ($rows->toArray() as $row) {
            $data = [
                'student_id' => $row['id_siswa'],
                'classroom_id' => $row['id_rombel'],
                'semester_id' => $classrooms->firstWhere('id', $row['id_rombel'])->semester_id,
            ];

            StudentSemester::create($data);
        }
    }

    /*
     * Rules
     */
    public function rules(): array
    {
        return [
            'id_siswa' => Rule::exists('stdnts', 'id'),
            'id_rombel' => Rule::exists('acdmc_classrooms', 'id'),
        ];
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }
}