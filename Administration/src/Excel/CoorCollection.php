<?php

namespace Modules\Administration\Excel;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class CoorCollection implements ToCollection
{
    public function collection(Collection $rows)
    {
        return $rows->filter(function ($row) {
            return !empty($row[1]);
        })->map(function ($row) {
            return [
                'B' => $row[1],
                'C' => $row[2],
                'D' => $row[3],
                'E' => $row[4],
            ];
        });
    }
}
