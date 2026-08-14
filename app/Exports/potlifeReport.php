<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class potlifeReport implements FromQuery, WithHeadings, WithMapping, WithChunkReading,WithEvents
{
     use Exportable;
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // 🔥 QUERY (copy dari controller kamu)
    public function query()
    {
        return DB::table('chemical___moves as a')
            ->select(
                'a.*',
                'a.created_at as time_mixing',
                'b.code_chemical',
                'b.model',
                'b.supplier',
                'b.component',
                'b.type',
                'b.adhesive_kind'
            )
            ->leftJoin('chemical___databases as b', function ($join) {
                $join->on('a.code_chemical', '=', 'b.code_chemical');
                $join->on('a.model', '=', 'b.model');
            })
            ->whereMonth('a.created_at', $this->month)
            ->whereYear('a.created_at', $this->year)
            ->orderBy('a.created_at', 'asc');
    }

    // 🔥 HEADER EXCEL
    public function headings(): array
    {
        return [
            'No',
            'Barcode ID',
            'Line / Cell',
            'Model',
            'Supplier',
            'Type',
            'Code Chemical',
            'Adhesive Kind',
            'Gram',
            'LOT Number',
            'Mixing Time',
            'Expired On',
            'Treatment'
        ];
    }

    // 🔥 MAPPING DATA (logika kamu pindah ke sini)
    protected $no = 0;

    public function map($a): array
    {
        $this->no++;

        // === LOGIKA KAMU ===
        $convert = strtotime(date('Y-m-d H:i', strtotime($a->time_mixing)));

        $durasiExpire = ($a->minutes == "-") ? 0 : $a->minutes;

        if ($a->time_mixing) {
            $time_mixing = date('Y-m-d H:i', $convert);
            $expire_on = date('Y-m-d H:i', $convert + ($durasiExpire * 60));
        } else {
            $time_mixing = '';
            $expire_on = '';
        }

        return [
            $this->no,
            $a->id_barcode,
            $a->line,
            $a->model,
            $a->supplier,
            $a->type,
            $a->code_chemical,
            $a->adhesive_kind,
            $a->gram . 'g',
            $a->lot_number,
            $time_mixing,
            $expire_on,
            $a->option
        ];
    }

    // 🔥 CHUNK (ini yang bikin ga timeout)
    public function chunkSize(): int
    {
        return 1000; // bisa 500 / 2000 tergantung server
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // 🔢 Hitung total row
                $totalRows = $sheet->getHighestRow();

                // 🔥 1. Style HEADER
                $sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => [
                            'argb' => 'FFDAEEF3', // warna biru muda
                        ],
                    ],
                ]);

                // 🔥 2. BORDER SEMUA TABLE
                $sheet->getStyle('A1:M'.$totalRows)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // 🔥 3. AUTO WIDTH (biar ga gepeng)
                foreach(range('A','M') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

            },
        ];
    }
}

