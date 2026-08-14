<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" <script
    src="{{ asset('js/Chart.js') }}"></script>
<script>
    var role = {!! json_encode($role) !!};
    var any = {!! json_encode($errors->any()) !!};
    var alert_err = {!! json_encode($errors->first()) !!};
    $(document).ready(function() {
        if (any != "") {
            alert(alert_err);
        }
        main();
        // if (role==0) {
        // var intervalMain = setInterval(function() {main();}, 10000);
        // }
        $('#date_search').on('change', function(event) {
            main();
        })
        $('#line_search').on('change', function(event) {
            main();
        })
    });

    function trackPO(po_wide) {
        $('#modal-show-data-detail-perline').modal('hide');
        $('#modalTrackingPO').modal('show');
        $('#poTrackingTitle').html(po_wide);
        $('#tracingIncomingPO').html('');
        $('#tracingOutputPO').html('');
        $.ajax({
            url: "{{ route('output_stf.trackingPO') }}",
            method: "get",
            data: {
                po_wide: po_wide
            },
            dataType: 'JSON',
            beforeSend: function() {
                // Show image container
                $("#nav-loading2").show();
            },
            success: function(data) {
                $('#tracingIncomingPO').html(data.tbody_input);
                $('#tracingOutputPO').html(data.tbody_output);
            },
            complete: function() {
                $("#nav-loading2").hide();
            }
        });
    }

    function refreshMain() {
        main();
    }

    function backToDataPerLine() {
        when = $('#date_search').val();
        var arrWhen = when.split("-");
        when = arrWhen[0] + arrWhen[1] + arrWhen[2];
        line = $('#line_search').find(":selected").val();
        shift = $('#shift_search').find(":selected").val();
        $('#modalTrackingPO').modal('hide');
        getBTSperDay(when, shift, line);
    }

    function getBTSperDay(when, shift, line) {
        main();
        $('#modal-show-data-detail-perline').modal('show');
        $('#tbody-detail-perline').html('');
        $.ajax({
            url: "{{ route('output_stf.detailBTS') }}",
            method: "get",
            data: {
                when: when,
                shift: shift,
                line: line
            },
            dataType: 'JSON',
            beforeSend: function() {
                // Show image container
                $("#nav-loading2").show();
            },
            success: function(data) {
                $('#tbody-detail-perline').html(data.table);
                $('#date-modal-detail-perline').html(data.when);
                $('#line-modal-detail-perline').html(data.line);
            },
            complete: function() {
                $("#nav-loading2").hide();
            }
        });
    }

    function openDetailPerDay() {
        when = $('#date_search').val();
        line = $('#line_search').find(":selected").val();
        shift = $('#shift_search').find(":selected").val();
        $.ajax({
            url: "{{ route('output_stf.getData.detail_gabungan') }}",
            method: "get",
            data: {
                when: when,
                pengawas: pengawas,
                shift: shift
            },
            dataType: 'JSON',
            beforeSend: function() {
                // Show image container
                $("#nav-loading").show();
            },
            success: function(data) {
                $('#detail-perline').html(data.table);
                $('#pengawas-modal-detail-perline').html(data.nama_pengawas);
            },
            complete: function() {
                $("#nav-loading").hide();
            }
        });
    }

    function main() {
        when = $('#date_search').val();
        line = $('#line_search').find(":selected").val();
        shift = $('#shift_search').find(":selected").val();
        $.ajax({
            url: "{{ route('output_stf.main') }}",
            method: "get",
            data: {
                when: when,
                line: line,
                shift: shift
            },
            dataType: 'JSON',
            beforeSend: function() {
                // Show image container
                $("#nav-loading").show();
            },
            success: function(data) {
                if (data.err) {
                    $('#tb_perCell').html('');
                    $('#tb_data').html('');
                    if (role != 0) {
                        createAlert('', 'Gagal!', data.err, data.color, true, true, 'pageMessages');
                    }
                } else {
                    $('#tb_data').html(data.tb_data);
                    $('#tb_perCell').html(data.tb_perCell);
                    $('#buymonth_list').html(data.bm_list);
                }
            },
            complete: function() {
                $("#nav-loading").hide();
            }
        });
    }

    function createAlert(title, summary, details, severity, dismissible, autoDismiss, appendToId) {
        var iconMap = {
            info: "fa fa-info-circle",
            success: "fa fa-thumbs-up",
            warning: "fa fa-exclamation-triangle",
            danger: "fa ffa fa-exclamation-circle"
        };

        var iconAdded = false;

        var alertClasses = ["alert", "animated", "flipInX"];
        alertClasses.push("alert-" + severity.toLowerCase());

        if (dismissible) {
            alertClasses.push("alert-dismissible");
        }

        var msgIcon = $("<i />", {
            "class": iconMap[severity] // you need to quote "class" since it's a reserved keyword
        });

        var msg = $("<div />", {
            "class": alertClasses.join(" ") // you need to quote "class" since it's a reserved keyword
        });

        if (title) {
            var msgTitle = $("<h4 />", {
                html: title
            }).appendTo(msg);

            if (!iconAdded) {
                msgTitle.prepend(msgIcon);
                iconAdded = true;
            }
        }

        if (summary) {
            var msgSummary = $("<strong />", {
                html: summary
            }).appendTo(msg);

            if (!iconAdded) {
                msgSummary.prepend(msgIcon);
                iconAdded = true;
            }
        }

        if (details) {
            var msgDetails = $("<p />", {
                html: details
            }).appendTo(msg);

            if (!iconAdded) {
                msgDetails.prepend(msgIcon);
                iconAdded = true;
            }
        }


        if (dismissible) {
            var msgClose = $("<span />", {
                "class": "close", // you need to quote "class" since it's a reserved keyword
                "data-dismiss": "alert",
                html: "<i class='fa fa-times-circle'></i>"
            }).appendTo(msg);
        }

        $('#' + appendToId).prepend(msg);

        if (autoDismiss) {
            setTimeout(function() {
                msg.addClass("flipOutX");
                setTimeout(function() {
                    msg.remove();
                }, 1000);
            }, 5000);
        }
    }

    function chartBTS(label, data) {
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [{
                    label: '#BTS %',
                    data: data,
                    backgroundColor: 'rgba(196,215,155,255)',
                    borderColor: 'rgba(220,228,228)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                showTooltips: false,
                scales: {
                    yAxes: [{
                        ticks: {

                            min: 0,
                            max: 100,
                            callback: function(value) {
                                return value + "%"
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: "Percentage"
                        }
                    }]
                },
                plugins: {
                    title: {
                        display: true,
                        text: "Graph"
                    },
                    legend: {
                        display: false,
                    },
                    datalabels: {
                        color: 'blue',
                        anchor: 'end',
                        align: 'right',
                        labels: {
                            title: {
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            }
        });
    }
</script>
<script></script>
