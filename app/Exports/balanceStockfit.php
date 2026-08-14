<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class balanceStockfit implements FromView
{
    use Exportable;

    public function __construct($query)
    {
        $this->query = $query;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.balanceStockfit', [
            'data' => $this->query
        ]);
    }
}
