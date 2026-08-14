<table>
    <thead>
        <tr>
            <th colspan="11" style="text-align: center; font-size:24px; font-weight:bold">PACEMAKER DATA</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th>Date : </th>
            {{-- <th>{{ date('Y-m-d') }}</th> --}}
            <th>{{ $data['date'] }}</th>
        </tr>
        <tr>
        </tr>
        <tr>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">No</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Shift</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Pengawas</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Line</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Jam</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">PO</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Wid</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Qty Order</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Size</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Qty</th>
            <th class="align-middle" style="width:60px ;background-color:#f4b484;font-weight:bold;text-align:center;vertical-align:middle;border: 3px solid black;border-collapse: collapse;font-size:12px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no=0;
        @endphp
        @foreach ($data['getData'] as $key => $a)
            @php
                if ($a->status == "Ready") {
                    $color = "#17a2b8";
                }else{
                    $color = "#28a745";
                }
                $no++;
            @endphp
            <tr>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $no }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->shift }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->nama_pengawas }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->line }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->jam }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->po }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->wide }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->qty_order }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->size_name }}</td>
                <td style="width:60px; border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->qty }}</td>
                <td style="width:60px; background-color:{{ $color }};  border: 3px solid black;border-collapse: collapse; text-align:center; font-size:10px">{{ $a->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
