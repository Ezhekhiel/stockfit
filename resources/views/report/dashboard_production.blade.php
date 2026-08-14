<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- amCharts -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <title>Bootstrap + amCharts</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            /* Center secara horizontal */
            align-items: center;
            /* Middle secara vertikal */
            background-color: #f8f9fa;
            /* Warna latar belakang opsional */
            overflow: hidden;
        }

        /* Atur lebar maksimal kontainer dashboard agar tidak terlalu melar */
        .app-content {
            width: 100%;
            max-width: 4000px;
            /* Batasi lebar maksimal jika diperlukan */
            padding: 10px;
        }

        /* Pertahankan ukuran tinggi kolom kiri dan kanan */
        .dp-left,
        .dp-right {
            display: flex;
            flex-direction: column;
            height: 94vh;
            /* Menggunakan vh agar dinamis mengikuti tinggi window Browsershot */
        }

        .dp-table {
            flex: 7.5;
            min-height: 0;
            margin-bottom: 12px;
        }

        .dp-model {
            flex: 2.5;
            min-height: 0;
        }

        .dp-chart1 {
            flex: 1;
            min-height: 0;
            margin-bottom: 12px;
        }

        .dp-chart2 {
            flex: 1;
            min-height: 0;
        }

        .dp-table>.card,
        .dp-model>.card,
        .dp-chart1>.card,
        .dp-chart2>.card {
            height: 100%;
        }

        .dp-table .card-body,
        .dp-model .card-body,
        .dp-chart1 .card-body,
        .dp-chart2 .card-body {
            height: 100%;
            overflow: hidden;
        }

        .dp-table .table-responsive,
        .dp-model .table-responsive {
            height: 100%;
            overflow: auto;
        }

        .chart-box {
            width: 100%;
            height: 100%;
        }

        table {
            font-size: clamp(23px, 1.15vw, 28px);
            white-space: nowrap;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        @media(max-width:991.98px) {

            .dp-table {
                height: 500px;
            }

            .dp-model {
                height: 250px;
                margin-bottom: 12px;
            }

            .dp-chart1,
            .dp-chart2 {
                height: 350px;
                margin-bottom: 12px;
            }
        }

        tr.double-row td {
            border-bottom: 4px double #000 !important;
        }
    </style>
</head>

<body>

    <div class="app-content">

        <div class="container-fluid">
            <div class="row m-2 mt-2">

                <!-- LEFT -->
                <div class="col-12 col-xl-6 mb-3 mb-xl-0 dp-left">

                    <!-- TABLE CELL -->
                    <div class="dp-table">

                        <div class="card shadow-sm">

                            <div class="card-body p-1">

                                <div class="table-responsive">

                                    <table class="table table-bordered table-sm text-center align-middle">

                                        <thead class="table-primary">
                                            <tr>
                                                <th>Cell</th>
                                                <th>Model</th>
                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    Target /<br> Hrs</th>
                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    WH</th>
                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    Daily<br> Target</th>

                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    WIP</th>
                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    Input</th>
                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    Output<br>(Shift 1)</th>
                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    Output<br>(Shift 2)</th>
                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    Total<br>Output<br>S1+S2</th>

                                                <th
                                                    style="width: 150px; min-width: 150px; max-width: 150px; vertical-align: middle;">
                                                    WIP<br> Update</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>

                                        <tbody id="summaryTable">

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- TABLE MODEL -->
                    <div class="dp-model">

                        <div class="card shadow-sm">

                            <div class="card-body p-1">

                                <div class="table-responsive">

                                    <table class="table table-bordered table-sm text-center align-middle">

                                        <thead class="table-primary">
                                            <tr>
                                                <th>Model</th>
                                                <th>Total Cell</th>
                                                <th>Target</th>
                                                <th>Output</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>

                                        <tbody id="summaryTableModel">
                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-12 col-xl-6 dp-right">

                    <div class="dp-chart1">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <div id="chartInputOutput" class="chart-box"></div>

                            </div>

                        </div>

                    </div>

                    <div class="dp-chart2">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <div id="chartWIP" class="chart-box"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mengonversi data dari controller Laravel ke Object JavaScript secara aman
            const dataProduction = @json($data_production);
            const cellTarget = @json($cell_target);
            const dataByModel = @json($data_by_model);

            // Langsung panggil fungsi render menggunakan data yang dibawa saat page load
            if (dataProduction && cellTarget) {
                renderMap(dataProduction, cellTarget);
            }

            if (dataByModel) {
                renderSummaryModel(dataByModel);
            }
        });

        function renderSummaryModel(data) {
            let html = '';

            $.each(data, function(index, item) {
                let persen = item.percent;
                let bgColor = '';
                if (persen >= 95) {
                    bgColor = '#00b050';
                } else if (persen > 80 && persen < 95) {
                    bgColor = '#ffc000';
                } else {
                    bgColor = '#ff0000';
                }
                html += '<tr>';
                html +=
                    `
                            <td>${item.model}</td>
                            <td>${item.total_cell}</td>
                            <td>${item.target}</td>
                            <td>${item.output}</td>
                            <td style="background:${bgColor};color:#fff;font-weight:bold;text-align:center">${item.percent}%</td>`;

                html += '</tr>';
            });
            $('#summaryTableModel').html(html);
        }

        function renderMap(data, arrTarget) {
            let html = '';
            let chartData = [];

            $.each(data, function(cell, style) {

                let cellRowspan = 0;
                let cellTotalOutput = 0;
                let cellTotalInput = 0;
                let cellTotalWipBaru = 0;

                let search_target = arrTarget.find(x => x.cell === cell);
                let daily_target = Math.floor(search_target.target * search_target.working_hour);

                // HITUNG TOTAL CELL DULU
                $.each(style, function(s, items) {
                    cellRowspan += items.length;

                    $.each(items, function(index, item) {
                        cellTotalOutput += (
                            parseInt(item.output_shift_1_today || 0) +
                            parseInt(item.output_shift_2_today || 0)
                        );
                        cellTotalInput += parseInt(item.input_today || 0);
                        cellTotalWipBaru += parseInt(item.wip_baru || 0);
                    });
                });

                chartData.push({
                    "cell": cell,
                    "input": cellTotalInput,
                    "output": cellTotalOutput,
                    "wip_baru": cellTotalWipBaru,
                    "target": daily_target
                });

                let firstCell = true;

                $.each(style, function(s, items) {

                    $.each(items, function(index, item) {
                        let input_lama = parseInt(item.input_lama);
                        let output_lama = parseInt(item.output_lama);

                        let output_shift_1_today = parseInt(item.output_shift_1_today);
                        let output_shift_2_today = parseInt(item.output_shift_2_today);


                        // ✅ PAKAI TOTAL CELL
                        let percent = Math.floor((cellTotalOutput / daily_target) * 100);
                        let bgColor = '';
                        if (percent >= 95) {
                            bgColor = '#00b050';
                        } else if (percent > 80 && percent < 95) {
                            bgColor = '#ffc000';
                        } else {
                            bgColor = '#ff0000';
                        }

                        html += '<tr>';

                        if (firstCell) {
                            html += `
                                <td rowspan="${cellRowspan}" class="align-middle fw-bold">
                                    ${cell}
                                </td>
                            `;
                        }

                        html += `<td>${item.style}</td>`;

                        if (firstCell) {
                            html += `
                                <td rowspan="${cellRowspan}">${search_target.target}</td>
                                <td rowspan="${cellRowspan}">${search_target.working_hour}</td>
                                <td rowspan="${cellRowspan}">${daily_target}</td>
                            `;
                        }

                        html += `
                            <td>${item.wip_lama}</td>
                            <td>${item.input_today}</td>
                            <td>${item.output_shift_1_today}</td>
                            <td>${item.output_shift_2_today}</td>
                            <td>${item.output_today}</td>
                            <td>${item.wip_baru}</td>`;
                        if (firstCell) {
                            html += `
                                <td style="background:${bgColor};color:#fff;font-weight:bold;text-align:center" rowspan="${cellRowspan}">${percent}%</td>
                            `;
                            firstCell = false;
                        }


                        html += '</tr>';
                    });
                });
            });
            $('#summaryTable').html(html);
            chartData.forEach(item => {
                item.target *= 1.5;
            });
            createAmChart(chartData);
            createWipChart(chartData);
        }
        var myInputOutputChartRoot = null;

        function createAmChart(chartData) {

            // JIKA ROOT SUDAH ADA, LANGSUNG DISPOSE DI SINI
            if (myInputOutputChartRoot) {
                myInputOutputChartRoot.dispose();
                myInputOutputChartRoot = null;
            }

            // Initialize Root baru dan simpan ke variabel global
            let root = am5.Root.new("chartInputOutput");
            myInputOutputChartRoot = root;

            // Set Theme
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Create Chart
            let chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "none",
                wheelY: "none",
                layout: root.verticalLayout
            }));
            // ---- SETTING KURSOR UNTUK HOVER EFFECT ----
            // Ini membuat garis pemandu (crosshair) muncul dan menyatukan tooltip saat di-hover
            let cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "none"
            }));
            cursor.lineY.set("visible", false); // Sembunyikan garis horizontal cursor jika mengganggu
            cursor.lineX.set("stroke", am5.color(0xaaaaaa)); // Warna garis vertikal pemandu

            // Create Axes
            let xRenderer = am5xy.AxisRendererX.new(root, {
                minGridDistance: 30
            });
            xRenderer.labels.template.setAll({
                rotation: -45,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 15
            });

            let xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "cell",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));
            xAxis.data.setAll(chartData);

            let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {})
            }));

            // 1. SERIES: INPUT (Kolom Biru)
            let seriesInput = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Input",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "input",
                categoryXField: "cell",
                clustered: true,
                fill: am5.color(0x00a2e8), // Biru cerah sesuai gambar
                stroke: am5.color(0x00a2e8)
            }));

            seriesInput.columns.template.setAll({
                width: am5.percent(80),
                tooltipText: "{name} ({categoryX}): [bold]{valueY}[/]",
                tooltipY: am5.percent(10)
            });

            // Menambahkan Label Angka di dalam Kolom Input
            seriesInput.bullets.push(function() {
                return am5.Bullet.new(root, {
                    locationY: 0.5,
                    child: am5.Label.new(root, {
                        text: "{valueY}",
                        fill: am5.color(0xffffff),
                        outset: false,
                        centerX: am5.p50,
                        centerY: am5.p50,
                        populateText: true,
                        rotation: -90
                    })
                });
            });
            seriesInput.data.setAll(chartData);

            // 2. SERIES: OUTPUT CCS (Kolom Hijau)
            let seriesOutput = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Output CCS",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "output",
                categoryXField: "cell",
                clustered: true,
                fill: am5.color(0x00b050), // Hijau sesuai gambar
                stroke: am5.color(0x00b050)
            }));

            seriesOutput.columns.template.setAll({
                width: am5.percent(80),
                tooltipText: "{name} ({categoryX}): [bold]{valueY}[/]"
            });

            // Menambahkan Label Angka di dalam Kolom Output
            seriesOutput.bullets.push(function() {
                return am5.Bullet.new(root, {
                    locationY: 0.5,
                    child: am5.Label.new(root, {
                        text: "{valueY}",
                        fill: am5.color(0xffffff),
                        outset: false,
                        centerX: am5.p50,
                        centerY: am5.p50,
                        populateText: true,
                        rotation: -90
                    })
                });
            });
            seriesOutput.data.setAll(chartData);

            // 3. SERIES: TARGET (Garis Merah)
            let seriesTarget = chart.series.push(am5xy.LineSeries.new(root, {
                name: "Target",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "target",
                categoryXField: "cell",
                stroke: am5.color(0xe46c0a), // Warna oranye/merah target
                strokeWidth: 3,
                // Kustomisasi Tooltip saat di-hover
                tooltip: am5.Tooltip.new(root, {
                    labelText: "[bold]{name}[/]: {valueY}",
                    pointerOrientation: "horizontal"
                })
            }));

            // Membuat titik (bullet) kecil pada garis target agar area hover lebih sensitif
            seriesTarget.bullets.push(function() {
                return am5.Bullet.new(root, {
                    child: am5.Circle.new(root, {
                        radius: 4,
                        fill: am5.color(0xe46c0a)
                    })
                });
            });
            seriesTarget.data.setAll(chartData);

            // Menambahkan Legend di bagian bawah chart
            let legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50,
                layout: root.horizontalLayout
            }));
            legend.data.setAll(chart.series.values);

            // Animasi saat chart muncul
            seriesInput.appear(1000);
            seriesOutput.appear(1000);
            chart.appear(1000, 100);
        }

        var myWipChartRoot = null;

        function createWipChart(chartData) {

            // JIKA ROOT SUDAH ADA, LANGSUNG DISPOSE
            if (myWipChartRoot) {
                myWipChartRoot.dispose();
                myWipChartRoot = null;
            }

            // Initialize Root baru
            let root = am5.Root.new("chartWIP");
            myWipChartRoot = root;

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            let chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "none",
                wheelY: "none",
                layout: root.verticalLayout
            }));

            // Tambahkan kursor hover
            let cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "none"
            }));
            cursor.lineY.set("visible", false);
            cursor.lineX.set("stroke", am5.color(0xaaaaaa));

            // X Axis
            let xRenderer = am5xy.AxisRendererX.new(root, {
                minGridDistance: 30
            });
            xRenderer.labels.template.setAll({
                rotation: -45,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 15
            });

            let xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "cell",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));
            xAxis.data.setAll(chartData);

            // Y Axis
            let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {})
            }));

            // 1. SERIES: WIP BARU (Kolom Biru Gelap / Teal sesuai gambar)
            let seriesWip = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "WIP",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "wip_baru",
                categoryXField: "cell",
                fill: am5.color(0x115f82), // Warna teal gelap sesuai gambar
                stroke: am5.color(0x115f82),
                tooltip: am5.Tooltip.new(root, {
                    labelText: "[bold]{name}[/]: {valueY}",
                    pointerOrientation: "horizontal"
                })
            }));

            seriesWip.columns.template.setAll({
                width: am5.percent(60), // Ukuran single bar lebih lebar sedikit
                tooltipY: am5.percent(10)
            });

            // Menambahkan Label Angka Vertikal di dalam Batang WIP
            seriesWip.bullets.push(function() {
                return am5.Bullet.new(root, {
                    locationY: 0.5,
                    child: am5.Label.new(root, {
                        text: "{valueY}",
                        fill: am5.color(0xffffff),
                        centerX: am5.p50,
                        centerY: am5.p50,
                        populateText: true,
                        rotation: -90
                    })
                });
            });
            seriesWip.data.setAll(chartData);

            // 2. SERIES: TARGET (Garis Merah/Oranye)
            let seriesTarget = chart.series.push(am5xy.LineSeries.new(root, {
                name: "Target",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "target",
                categoryXField: "cell",
                stroke: am5.color(0xe46c0a), // Warna oranye kemerahan
                strokeWidth: 3,
                tooltip: am5.Tooltip.new(root, {
                    labelText: "[bold]{name}[/]: {valueY}",
                    pointerOrientation: "horizontal"
                })
            }));

            seriesTarget.strokes.template.setAll({
                strokeWidth: 3
            });

            // Bullet titik pada garis target
            seriesTarget.bullets.push(function() {
                return am5.Bullet.new(root, {
                    child: am5.Circle.new(root, {
                        radius: 4,
                        fill: am5.color(0xe46c0a)
                    })
                });
            });
            seriesTarget.data.setAll(chartData);

            // Legend
            let legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50,
                layout: root.horizontalLayout
            }));
            legend.data.setAll(chart.series.values);

            // Animasi muncul
            seriesWip.appear(1000);
            chart.appear(1000, 100);
        }
    </script>

</body>

</html>
