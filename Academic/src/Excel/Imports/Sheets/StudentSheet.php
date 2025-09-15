<?php

namespace Digipemad\Sia\Academic\Excel\Imports\Sheets;

use Digipemad\Sia\Academic\Models\Academic;
use Digipemad\Sia\Academic\Models\Student;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Illuminate\Support\Collection;

class StudentSheet implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    /**
     * On each row
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row) {
            $data = [
                'acdmc_id' => $row['id_tahun_ajaran'],
                'name' => $row['nama_siswa'] ?? null,
                'nis' => $row['nis'] ?? null,
                'nisn' => $row['nisn'] ?? null,
                'nik' => $row['nik'] ?? null,
                'pob' => $row['tempat_lahir'] ?? null,
                'dob' => isset($row['tanggal_lahir']) ? Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d') : null,
                'sex' => $row['jk'] ?? null,
                'entered_at' => isset($row['tanggal_masuk']) ? Date::excelToDateTimeObject($row['tanggal_masuk'])->format('Y-m-d') : null,
            ];

            Student::completeInsert($data, $data['nis']);
        }
    }

    /*
     * Rules
     */
    public function rules(): array
    {
        return [
            'id_tahun_ajaran' => Rule::exists('acdmcs', 'id'),
            'nis' => [Rule::unique('users', 'username'), Rule::unique('stdnts', 'nis')],
            'nisn' => ['nullable', Rule::unique('stdnts', 'nisn')],
            'dob' => ['nullable', 'date_format:Y-m-d'],
            'entered_at' => ['nullable', 'date_format:Y-m-d']
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