<?php

namespace Digipemad\Sia\Administration\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportTeacher implements FromView, WithEvents, ShouldAutoSize, WithTitle
{
    use Exportable;

    public $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function title(): string
    {
        return 'Daftar guru';
    }

    public function view(): View
    {
        return view('administration::services.schedules_teacher.template.excel', ['employees' => $this->employees]);
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $sheet->getStyle('A3:D3')->applyFromArray($styleArray);

                $sheet->mergeCells('A1:D2');
                $sheet->setCellValue('A1', 'Data Pengajar');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A3:D3')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFFF00',
                        ],
                    ],
                ]);

                $sheet->getColumnDimension('E')->setVisible(false);

                $sheet = $event->sheet->getDelegate();

                $sheet->getProtection()->setSheet(true);

                 // Membuka proteksi pada rentang tertentu
            foreach (range('A', 'E') as $column) {
                $sheet->getStyle($column . '1:' . $column . '1000')
                    ->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            }

            $sheet->setBreak('F1', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN);

            $sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);

            $sheet->getHeaderFooter()->setOddFooter('');
            $sheet->getHeaderFooter()->setEvenFooter('');
            $sheet->getHeaderFooter()->setOddHeader('');
            $sheet->getHeaderFooter()->setEvenHeader('');
            },
        ];
    }
}
