<table>
    <thead>
        <tr>
            <th colspan="13" style="text-align: center; font-size:24px; font-weight:bold">DAILY BALANCE STOCKFIT</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th>DATA :</th>
            <th colspan="3">BALANCE STOCKFIT</th>
        </tr>
        <tr>
            <th>Tanggal Print : </th>
            <th colspan="3">{{ date("Y-m-d") }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">BM</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">CELL</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">STYLE</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">ARTICLE</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">WIDE</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">G</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">PO</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">XFD</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">QTY ORDER</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" colspan="3">STOCKFIT</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">BALANCE INPUT</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3" rowspan="2">BALANCE OUTPUT</th>
        </tr>
        <tr>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3">INCOMING</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3">OUTGOING</th>
            <th style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#daeef3">WIP</th>
        </tr>
    </thead>
    <tbody>
        @php
            $cell_last = '';
            // set variable total qty
                $tot_qty=[
                    'qty_order'=>0,
                    'input_stf'=>0,
                    'output_stf'=>0,
                ];
        @endphp
            @foreach ($data['c'] as $key => $value)
                @if ($cell_last != $value['cell'] && $cell_last!='')
                    <tr style="text-align: center;">
                        <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#a9aca2" colspan="8">Total {{ $cell_last }}</td>
                        <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#a9aca2">{{ number_format($tot_qty['qty_order']) }}</td>
                        <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#a9aca2">{{ number_format($tot_qty['input_stf']) }}</td>
                        <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#a9aca2">{{ number_format($tot_qty['output_stf']) }}</td>
                        <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#a9aca2">{{ number_format($tot_qty['input_stf']-$tot_qty['output_stf']) }}</td>
                        <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#a9aca2">{{ number_format($tot_qty['qty_order']-$tot_qty['input_stf']) }}</td>
                        <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;; font-size:14px;font-weight:bold; background-color:#a9aca2">{{ number_format($tot_qty['qty_order']-$tot_qty['output_stf']) }}</td>
                    </tr>
                    {{ $tot_qty['qty_order']=0; }}
                    {{ $tot_qty['input_stf']=0; }}
                    {{ $tot_qty['output_stf']=0; }}
                @else
                    <tr>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['buymonth'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['cell'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['style'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['article'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['wide'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['g'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['po'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ $value['xfd'] }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;">{{ number_format($value['qty']) }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;{{ $value['color_input_stf'] }}">{{ number_format($value['input_stf']) }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;{{ $value['color_output_stf'] }}">{{ number_format($value['output_stf']) }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;{{ $value['color_output_stf'] }}">{{ number_format($value['wip']) }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;{{ $value['color_balance_input'] }}">{{ number_format($value['balance_input']) }}</td>
                                    <td style="text-align:center;border: 3px solid black;border-collapse: collapse; width:130px;{{ $value['color_balance_output'] }}">{{ number_format($value['balance_output']) }}</td>
                                </tr>
                    {{ $tot_qty['qty_order']=$tot_qty['qty_order']+$value['qty']; }}
                    {{ $tot_qty['input_stf']=$tot_qty['input_stf']+$value['input_stf'];}}
                    {{ $tot_qty['output_stf']=$tot_qty['output_stf']+$value['output_stf'];}}
                @endif
                {{ $cell_last = $value['cell']; }}
            @endforeach

    </tbody>
</table>
