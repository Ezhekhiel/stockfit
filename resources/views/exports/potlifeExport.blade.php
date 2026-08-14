<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

    <table>
        <thead>
            <tr>
                <th style="text-align: center; font-size:24px; font-weight:bold" colspan="13">MIXING HISTORY REPORT</th>
            </tr>
            <tr>
                <th colspan="13" style="height:20px;"></th>
            </tr>
            <tr>
                <th colspan="2">Month Report</th>
                <th colspan="1">:{{ $month }}</th>
            </tr>
            <tr>
                <th colspan="2">Tanggal Print</th>
                <th colspan="1">:{{ date("Y-m-d") }}</th>
            </tr>
            <tr>
                <th></th>
            </tr>
            <tr>
                <th style="text-align:center;border: 3px solid black; width:50px; font-size:14px;font-weight:bold; background-color:#daeef3">No</th>
                <th style="text-align:center;border: 3px solid black; width:130px; font-size:14px;font-weight:bold; background-color:#daeef3">Barcode ID</th>
                <th style="text-align:center;border: 3px solid black; width:150px; font-size:14px;font-weight:bold; background-color:#daeef3">Line / Cell</th>
                <th style="text-align:center;border: 3px solid black; width:130px; font-size:14px;font-weight:bold; background-color:#daeef3">Model's</th>
                <th style="text-align:center;border: 3px solid black; width:170px; font-size:14px;font-weight:bold; background-color:#daeef3">Adhesive Suppliers</th>
                <th style="text-align:center;border: 3px solid black; width:170px; font-size:14px;font-weight:bold; background-color:#daeef3">Type of Adhesive</th>
                <th style="text-align:center;border: 3px solid black; width:150px; font-size:14px;font-weight:bold; background-color:#daeef3">Adhesive Name</th>
                <th style="text-align:center;border: 3px solid black; width:150px; font-size:14px;font-weight:bold; background-color:#daeef3">Adhesive Kind</th>
                <th style="text-align:center;border: 3px solid black; width:170px; font-size:14px;font-weight:bold; background-color:#daeef3">Adhesive Usage Quantity (Gram)</th>
                <th style="text-align:center;border: 3px solid black; width:170px; font-size:14px;font-weight:bold; background-color:#daeef3">Adhesive LOT Number</th>
                <th style="text-align:center;border: 3px solid black; width:150px; font-size:14px;font-weight:bold; background-color:#daeef3">Mixing Time</th>
                <th style="text-align:center;border: 3px solid black; width:130px; font-size:14px;font-weight:bold; background-color:#daeef3">Expired On</th>
                <th style="text-align:center;border: 3px solid black; width:130px; font-size:14px;font-weight:bold; background-color:#daeef3">Threatment</th>
            </tr>
        </thead>
            <tbody>
                @foreach($rows as $no => $a)
                <tr>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $no+1 }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->id_barcode ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->line ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->model ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->supplier ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->type ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->code_chemical ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->adhesive_kind ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->gram ?? '' }}g</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->lot_number ?? '' }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ \Carbon\Carbon::parse($a->created_at)->format('Y-m-d H:i') }}</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">-</td>
                    <td style="text-align:center;border: 2px solid black; font-size:12px;">{{ $a->option ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
    </table>
</body>
</html>
