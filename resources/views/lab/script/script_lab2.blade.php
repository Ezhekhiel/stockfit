<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script>
    var arrDatabase = [];
    $(document).ready(function() {
        main();
        intervalMain = setInterval(function() {
            main();
        }, 60000);

        $('#form-update-main').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('lab.chemical.update') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#nav-loading").show();
                },
                success: function(data) {
                    if (data.alert == "Sukses!") {
                        $('#updateData').modal('hide');
                        main();
                    }
                    createAlert('', data.alert, data.text, data.color, true, true,
                        'pageMessages');
                },
                complete: function() {
                    $("#nav-loading").hide();
                }
            });
        });
        $('#form-mixing-modal').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('lab.chemical.mixing') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#nav-loading").show();
                },
                success: function(data) {
                    if (data.alert == "Sukses!") {
                        $('#mixing_modal').modal('hide');
                        main();
                    }
                    createAlert('', data.alert, data.text, data.color, true, true,
                        'pageMessages');
                },
                complete: function() {
                    $("#nav-loading").hide();
                }
            });
        });
        $('#addBarcode').on('click', function(event) {
            $('#addBarcode_modal').modal('show');
        });
        $('#model_search_mixing').on('keyup', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('lab.chemical.search_model_mixing') }}",
                method: "get",
                data: {
                    model: $(this).val()
                },
                dataType: 'JSON',
                beforeSend: function() {
                    $("#nav-loading").show();
                },
                success: function(data) {
                    $('#tbody_database').html(data.tbody_database);
                },
                complete: function(data) {
                    $("#nav-loading").hide();
                }
            });
        })
        $('#model_search_mixing_act').on('keyup', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('lab.chemical.search_model_mixing_act') }}",
                method: "get",
                data: {
                    model: $(this).val()
                },
                dataType: 'JSON',
                beforeSend: function() {
                    $("#nav-loading").show();
                },
                success: function(data) {
                    $('#tbody_database_act').html(data.tbody_database);
                },
                complete: function(data) {
                    $("#nav-loading").hide();
                }
            });
        })
        $('#search_data_model').on('keyup', function(event) {
            event.preventDefault();
            main();
        })
        $('#search_data').on('change', function(event) {
            event.preventDefault();
            main();
        })
        $('#mixingButton').on('click', function(event) {
            document.getElementById("form-mixing-modal").reset();
            $('#mixing_modal').modal('show');
        })
        $('#id_line_select').on('change', function(event) {
            if ($(this).val() != '') {
                var line_ = $('#id_line_modal').val();
                var coma = '';
                if (line_ != '') {
                    coma = ', ';
                }
                $('#id_line_modal').val(line_ + coma + $(this).val());
            }
        });
        $('#id_barcode_modal').on('change', function() {
            var text = $(this).val();
            if (text.includes('/')) {
                const split_text = text.split("/");
                $(this).val(split_text[6]);
            }
        });
        $('#exportButton').on('click', function(event) {
            $('#openModalExport').modal('toggle');
        });
        $('#select_option').on('change', function(event) {
            main();
        })
        $('#id_area_option').on('change', function() {
            var val = $(this).val();
            var lineSelect = $('#id_line_select');

            if (val === "") {
                lineSelect.html('');
            } else if (val === "C2B") {
                lineSelect.html(`
                                    <option value="">Select Cell</option>
                                    <option value="B1-1">B1-1</option>
                                    <option value="B1-2">B1-2</option>
                                    <option value="B1-3">B1-3</option>
                                    <option value="B1-4">B1-4</option>
                                    <option value="B1-5">B1-5</option>
                                    <option value="B1-6">B1-6</option>
                                    <option value="B1-7">B1-7</option>
                                    <option value="B1-8">B1-8</option>
                                    <option value="B1-9">B1-9</option>
                                    <option value="B1-10">B1-10</option>
                                    <option value="B1-11">B1-11</option>
                                    <option value="B1-12">B1-12</option>
                                    <option value="B1-13">B1-13</option>
                                    <option value="B1-14">B1-14</option>
                                    <option value="B1-15">B1-15</option>
                                    <option value="B1-16">B1-16</option>
                                    <option value="B1-17">B1-17</option>
                                    <option value="B1-18">B1-18</option>
                                    <option value="B1-19">B1-19</option>
                                    <option value="B1-20">B1-20</option>
                                    <option value="B1-21">B1-21</option>
                                    <option value="B1-22">B1-22</option>
                                    <option value="B1-23">B1-23</option>
                                    <option value="B1-24">B1-24</option>
                                    <option value="B1-25">B1-25</option>
                                    <option value="B1-26">B1-26</option>
                                    <option value="B2-1">B2-1</option>
                                    <option value="B2-2">B2-2</option>
                                    <option value="B2-3">B2-3</option>
                                    <option value="B2-4">B2-4</option>
                                    <option value="B2-5">B2-5</option>
                                    <option value="B2-6">B2-6</option>
                                    <option value="B2-7">B2-7</option>
                                    <option value="B2-8">B2-8</option>
                                    <option value="B2-9">B2-9</option>
                                    <option value="B2-10">B2-10</option>
                                    <option value="B2-11">B2-11</option>
                                    <option value="B2-12">B2-12</option>
                                `);
            } else if (val === "Stockfit") {
                lineSelect.html(`
                                    <option value="">Select Line</option>
                                    <option value="LINE - 1A">LINE - 1A</option>
                                    <option value="LINE - 1B">LINE - 1B</option>
                                    <option value="LINE - 2A">LINE - 2A</option>
                                    <option value="LINE - 2B">LINE - 2B</option>
                                    <option value="LINE - 3A">LINE - 3A</option>
                                    <option value="LINE - 3B">LINE - 3B</option>
                                    <option value="LINE - 4A">LINE - 4A</option>
                                    <option value="LINE - 4B">LINE - 4B</option>
                                    <option value="LINE - 5A">LINE - 5A</option>
                                    <option value="LINE - 5B">LINE - 5B</option>
                                    <option value="LINE - 6A">LINE - 6A</option>
                                    <option value="LINE - 6B">LINE - 6B</option>
                                    <option value="LINE - 7A">LINE - 7A</option>
                                    <option value="LINE - 7B">LINE - 7B</option>
                                    <option value="LINE - 8A">LINE - 8A</option>
                                    <option value="LINE - 8B">LINE - 8B</option>
                                    <option value="LINE - 9A">LINE - 9A</option>
                                    <option value="LINE - 9B">LINE - 9B</option>
                                    <option value="LINE - 10A">LINE - 10A</option>
                                    <option value="LINE - 10B">LINE - 10B</option>
                                    <option value="LINE - 11A">LINE - 11A</option>
                                    <option value="LINE - 11B">LINE - 11B</option>
                                    <option value="LINE - 12A">LINE - 12A</option>
                                    <option value="LINE - 12B">LINE - 12B</option>
                                    <option value="LINE - 13A">LINE - 13A</option>
                                    <option value="LINE - 13B">LINE - 13B</option>
                                    <option value="LINE - 14A">LINE - 14A</option>
                                    <option value="LINE - 14B">LINE - 14B</option>
                                    <option value="LINE - 15A">LINE - 15A</option>
                                    <option value="LINE - 15B">LINE - 15B</option>
                                    <option value="LINE - 16A">LINE - 16A</option>
                                    <option value="LINE - 16B">LINE - 16B</option>
                                    <option value="LINE - 17A">LINE - 17A</option>
                                    <option value="LINE - 17B">LINE - 17B</option>
                                `);
            } else {
                alert("Pilihan tidak dikenal!");
            }

        });
    });

    function updateMain(id_barcode) {
        $('#updateData').modal('show');
        $('#id_data').val(arrDatabase[id_barcode]['id']);
        $('#id_barcode_scan').html(arrDatabase[id_barcode]['id_barcode']);
        $('#id_model_update').html(arrDatabase[id_barcode]['model']);
        $('#id_supplier_update').html(arrDatabase[id_barcode]['supplier']);
        $('#id_component_update').html(arrDatabase[id_barcode]['component']);
        $('#id_type_update').html(arrDatabase[id_barcode]['type']);
        $('#id_code_update').val(arrDatabase[id_barcode]['code_chemical']);
        if (arrDatabase[id_barcode]['status'] == 'Not Yet') {
            $('#btn_finish_update').show();
            $('#btn_finish_update').attr('onclick', 'finishProcess("' + arrDatabase[id_barcode]['id'] + '")');
            $('#line_option_update').hide();
        } else {
            $('#btn_finish_update').hide();
            $('#line_option_update').show();
        }
    }

    function finishProcess(id) {
        $.ajax({
            url: "{{ route('lab.chemical.update_status') }}",
            method: "get",
            data: {
                id: id
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                if (data.alert == "Sukses!") {
                    $('#updateData').modal('hide');
                    main();
                }
                createAlert('', data.alert, data.text, data.color, true, true, 'pageMessages');
            },
            complete: function(data) {
                $("#nav-loading").hide();
            }
        });
    }

    function main() {
        var option = $("#select_option").val();
        var search = $('#search_data').val();
        $.ajax({
            url: "{{ route('lab.chemical.main') }}",
            method: "get",
            data: {
                option: option,
                search: search
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                $('#tbody').html(data.table);
                $('#line_option_update').html(data.select_option);
                if ($("#tbody_database").html() == "" || $("#tbody_database").html() == "") {
                    $('#tbody_database').html(data.tbody_database);
                    $('#tbody_database_act').html(data.tbody_database_act);
                }
                arrDatabase = data.arrDatabase;
            },
            complete: function(data) {
                $("#nav-loading").hide();
            }
        });
    }

    function printBarcode() {
        var checked = [];
        $("input[name='checkboxData[]']:checked").each(function() {
            checked.push($(this).val());
        });
        console.log(checked);

    }

    function openDatabaseChemical() {
        window.open('chemical/database', '_blank');
    }

    function selectAllCheckFunction() {
        if ($('input#selectAllCheck').is(':checked')) {
            $('.class-new').prop('checked', true);
        } else {
            $('.class-new').prop('checked', false);
        }
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
</script>
