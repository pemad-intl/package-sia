<?php

namespace Digipemad\Sia\Administration\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Modules\HRMS\Models\Employee;
use Modules\Core\Enums\PositionTypeEnum;

class ExportScheduleTeacher implements FromView, WithEvents, ShouldAutoSize, WithTitle
{
    use Exportable;

    public function title(): string
    {
        return 'Pengajuan jadwal guru';
    }

    public function view(): View
    {
        return view('administration::services.schedules_teacher.template.schedule');
    }

    private function getColumnName($index)
    {
        $column = '';
        while ($index >= 0) {
            $column = chr($index % 26 + 65) . $column;
            $index = intval($index / 26) - 1;
        }
        return $column;
    }

    public function iterationLoop($sheet, $callback, $row)
    {
        $startIndex = 2; // Kolom C
        $endIndex = 55; // Kolom BD (6 hari × 9 shift = 54 kolom)

        for ($i = $startIndex; $i <= $endIndex; $i += 9) {
            $startColumn = $this->getColumnName($i);
            $endColumn = $this->getColumnName($i + 8);

            $sheet->mergeCells($startColumn . $row . ':' . $endColumn . $row);
            $callback($sheet, $startColumn, $row);
        }

        return $sheet;
    }

    private function dayHeaders($sheet, $tr)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        foreach ($days as $index => $day) {
            $colIndex = 2 + ($index * 9); // 9 shift per hari
            $startCol = $this->getColumnName($colIndex);
            $endCol = $this->getColumnName($colIndex + 8);

            $cell = $startCol . $tr;
            $sheet->mergeCells("$startCol$tr:$endCol$tr");
            $sheet->setCellValue($cell, $day);

            $sheet->getStyle($cell)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFFF00');
        }
    }

    private function label($sheet, $label = '', $tr)
    {
        $this->iterationLoop($sheet, function ($sheet, $startColumn, $row) use ($label) {
            $cellToWrite = $startColumn . $row;
            $sheet->setCellValue($cellToWrite, $label);

            $sheet->getStyle($cellToWrite)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }, $tr);
    }

    private function shift($sheet, $tr)
    {
        $row = $tr;
        $startIndex = 2;
        $endIndex = 55;

        for ($i = $startIndex; $i <= $endIndex; $i += 9) {
            for ($shift = 0; $shift < 9; $shift++) {
                $col = $this->getColumnName($i + $shift);
                $cellToWrite = $col . $row;

                $sheet->setCellValue($cellToWrite, (string)($shift + 1));

                $sheet->getStyle($cellToWrite)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getColumnDimension($col)->setWidth(10);
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $styleArray = [
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $sheet->mergeCells('A1:A4');
                $sheet->setCellValue('A1', 'Nama Guru');
                $sheet->getColumnDimension('A')->setWidth(25);

                $sheet->mergeCells('B1:B4');
                $sheet->setCellValue('B1', 'ID Guru');
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('B')->setVisible(false);

                $lastCol = $this->getColumnName(55); 

                $sheet->mergeCells("C1:$lastCol" . '1');
                $sheet->setCellValue("C1", 'Data Pengajar');
                $sheet->getStyle("C1")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFFF00');

                $sheet->getStyle("A1:$lastCol" . '4')->applyFromArray($styleArray);
                $sheet->getStyle("C1")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $employeess = Employee::where('grade_id', userGrades())->with('user')
                    ->whereHas('position.position', fn($q) => $q->where('type', PositionTypeEnum::GURU))
                    ->get();

                $row = 5;
                foreach ($employeess as $emp) {
                    $sheet->setCellValue("A{$row}", $emp->user->name);
                    $sheet->setCellValue("B{$row}", $emp->id);
                    $row++;
                }

                $this->dayHeaders($sheet, 2);
                $this->label($sheet, 'SHIFT', 3);
                $this->shift($sheet, 4);

                $sheet->setBreak("$lastCol" . '1', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN);
                $sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);

                $sheet->getHeaderFooter()->setOddFooter('');
                $sheet->getHeaderFooter()->setEvenFooter('');
                $sheet->getHeaderFooter()->setOddHeader('');
                $sheet->getHeaderFooter()->setEvenHeader('');
            },
        ];
    }
}
