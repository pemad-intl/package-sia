<?php
namespace Modules\Administration\Excel;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RowSchedule
{

    private function processRows(Worksheet $worksheet)
    {
        dd($worksheet);
        $lastRow = $worksheet->getHighestRow();
    $lastColumn = $worksheet->getHighestColumn();
    $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

    dd([
        'lastRow' => $lastRow,
        'lastColumn' => $lastColumn,
        'lastColumnIndex' => $lastColumnIndex,
    ]);

    return $lastColumn;
    }
}
