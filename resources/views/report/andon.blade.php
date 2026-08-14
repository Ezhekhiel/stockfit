<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report Andon</title>

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background: #ddd;
        }
    </style>
</head>

<body>

    <h3 class="report-title">
        REPORT ANDON {{ now()->format('Y-m-d') }} Computer Stitching Centralize (Minutes)
    </h3>

    <table class="table table-bordered table-striped table-sm text-center">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Area</th>
                <th>Quality</th>
                <th>Mesin</th>
                <th>Material</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>

            @forelse($data_andon as $bagian => $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bagian }}</td>
                    <td>{{ $row['problem_1'] }} Minutes</td>
                    <td>{{ $row['problem_2'] }} Minutes</td>
                    <td>{{ $row['problem_3'] }} Minutes</td>
                    <td>
                        {{ $row['problem_1'] + $row['problem_2'] + $row['problem_3'] }}
                        Minutes
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="100" class="text-center">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>

</body>

</html>
