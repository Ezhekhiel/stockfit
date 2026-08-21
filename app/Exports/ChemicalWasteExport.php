<?php

namespace App\Exports;

use App\Models\chemical___waste;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ChemicalWasteExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithColumnWidths,
    WithEvents
{
    public function __construct(
        private string $startDate,
        private string $endDate,
        private string $area
    ) {}

    /**
     * Posisi header Excel dimulai dari A5
     */
    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * Data
     */
    public function collection(): Collection
    {
        return chemical___waste::with('chemical')
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ])
            ->where('area', $this->area)
            ->get()
            ->map(function ($item, $index) {

                return [
                    $index + 1,

                    $item->chemical->code_chemical ?? '-',

                    $item->chemical->model ?? '-',

                    $item->chemical->supplier ?? '-',

                    $item->chemical->type ?? '-',

                    $item->chemical->adhesive_kind ?? '-',

                    $item->gram,

                    $item->lot_number,

                    $item->created_at->format('Y-m-d H:i:s'),
                ];
            });
    }

    /**
     * Header
     */
    public function headings(): array
    {
        return [
            'No',
            'Code Chemical',
            'Model',
            'Adhesive Supplier',
            'Type of Adhesive',
            'Adhesive Kind',
            'Adhesive Usage Quantity (Gram)',
            'Adhesive Lot Number',
            'Created_at',
        ];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 28,
            'C' => 25,
            'D' => 24,
            'E' => 22,
            'F' => 20,
            'G' => 32,
            'H' => 25,
            'I' => 25,
        ];
    }

    /**
     * Styling Excel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | Title
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:I1');

                $sheet->setCellValue(
                    'A1',
                    'ADHESIVE WASTE MANAGEMENT SYSTEM'
                );

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => [
                            'rgb' => '1F2937',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(32);


                /*
                |--------------------------------------------------------------------------
                | Period
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A2:I2');

                $sheet->setCellValue(
                    'A2',
                    'Period : ' . $this->startDate . ' - ' . $this->endDate
                );

                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                ]);


                /*
                |--------------------------------------------------------------------------
                | Area
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A3:I3');

                $sheet->setCellValue(
                    'A3',
                    'Area : Building-1'
                );

                $sheet->getStyle('A3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                ]);


                /*
                |--------------------------------------------------------------------------
                | Header
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A5:I5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => [
                            'rgb' => '374151',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => [
                                'rgb' => 'D1D5DB',
                            ],
                        ],
                    ],
                ]);

                $sheet->getRowDimension(5)->setRowHeight(35);


                /*
                |--------------------------------------------------------------------------
                | Data
                |--------------------------------------------------------------------------
                */

                $highestRow = $sheet->getHighestRow();

                if ($highestRow >= 6) {

                    $sheet->getStyle("A6:I{$highestRow}")->applyFromArray([
                        'alignment' => [
                            'vertical' => 'center',
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => 'thin',
                                'color' => [
                                    'rgb' => 'D1D5DB',
                                ],
                            ],
                        ],
                    ]);

                    // Center beberapa kolom
                    $sheet->getStyle("A6:A{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal('center');

                    $sheet->getStyle("E6:I{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal('center');

                    // Quantity format
                    $sheet->getStyle("G6:G{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('0.00');
                }


                /*
                |--------------------------------------------------------------------------
                | Freeze Header
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A6');


                /*
                |--------------------------------------------------------------------------
                | Auto Filter
                |--------------------------------------------------------------------------
                */

                $sheet->setAutoFilter(
                    "A5:I{$highestRow}"
                );
            },
        ];
    }
}
