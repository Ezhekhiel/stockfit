<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        main('');
        $('#search_data_model').on('keyup', function(event) {
            event.preventDefault();
            main();
        })
        $('#form_add_database').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('lab.chemical.database.save') }}",
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
                        $('#modal_add_database').modal('hide');
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
        $('#id_area_option').on('change', function(event) {
            event.preventDefault();
            alert($(this).val());
        })

    });

    function main() {
        var model = $('#search_data_model').val();
        $.ajax({
            url: "{{ route('lab.chemical.database.main') }}",
            method: "get",
            data: {
                model: model
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                $('#tbody').html(data.tbody);
            },
            complete: function(data) {
                $("#nav-loading").hide();
            }
        });
    }

    function changeRadioModal(id) {
        $(".class_code").attr('name', '');
        $(".class_code").attr('disabled', true);

        $('#update_code_primer_' + id).attr('name', 'update_code_primer');
        $('#update_code_cement_' + id).attr('name', 'update_code_cement');

        $('#update_code_primer_' + id).attr('disabled', false);
        $('#update_code_cement_' + id).attr('disabled', false);

        $(".class_adhesive").attr('name', '');
        $(".class_adhesive").attr('disabled', true);

        $('#update_adhesive_primer_' + id).attr('name', 'update_adhesive_kind_primer');
        $('#update_adhesive_cement_' + id).attr('name', 'update_adhesive_kind_cement');

        $('#update_adhesive_primer_' + id).attr('disabled', false);
        $('#update_adhesive_cement_' + id).attr('disabled', false);
    }

    function updateDatabaseModal() {
        var id = $("input[name='radio-code']:checked").val();
        var model = $("#model_update_id").val();

        var code_primer = $("input[name=update_code_primer]").val();
        var code_cement = $("input[name=update_code_cement]").val();

        var last_code_primer = $("#last_code_primer_" + id).val();
        var last_code_cement = $("#last_code_cement_" + id).val();

        var adhesive_kind_primer = $("select[name='update_adhesive_kind_primer']").val();

        var adhesive_kind_cement = $("select[name=update_adhesive_kind_cement]").val();

        $.ajax({
            url: "{{ route('lab.chemical.database.update') }}",
            method: "get",
            data: {
                id: id,
                model_update: model,
                code_primer: code_primer,
                code_cement: code_cement,
                last_code_primer: last_code_primer,
                last_code_cement: last_code_cement,
                adhesive_kind_primer: adhesive_kind_primer,
                adhesive_kind_cement: adhesive_kind_cement
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                if (data.alert == 'Sukses!') {
                    var model = $("#search_data_model").val();
                    main(model);
                    $('#modal_update_database').modal('hide');
                }
                createAlert('', data.alert, data.text, data.color, true, true, 'pageMessages');
            },
            complete: function(data) {
                $("#nav-loading").hide();
            }
        });
    }

    function deleteDatabaseModal(data) {
        let arr_data = data.split("|");
        if (!$("input[name='radio-code']:checked").val()) {
            alert('Yout must select 1 radio button!')
        } else {
            $.ajax({
                url: "{{ route('lab.chemical.database.get_data_delete') }}",
                method: "get",
                data: {
                    model: arr_data[0],
                    a_supplier: arr_data[1]
                },
                dataType: 'JSON',
                beforeSend: function() {
                    $("#nav-loading").show();
                },
                success: function(data) {
                    $('#modal_update_database').modal('hide');
                    $('#delete_confirm').modal('show');
                    $('#tbody_data_delete').html(data.table);
                },
                complete: function(data) {
                    $("#nav-loading").hide();
                }
            });
        }

    }

    function deleteData(id) {
        $.ajax({
            url: "{{ route('lab.chemical.database.deleteDatabase') }}",
            method: "get",
            data: {
                id: id
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                if (data.alert == 'Sukses!') {
                    $('#delete_confirm').modal('hide');
                    main();
                }
                createAlert('', data.alert, data.text, data.color, true, true, 'pageMessages');

            },
            complete: function(data) {
                $("#nav-loading").hide();
            }
        });
    }

    function updateDatabase(data, kind) {
        $('#model_update_id').val(data);
        $('#modal_update_database').modal('show');
        $('#button_delete_modal').attr('onclick', 'deleteDatabaseModal("' + data + '")')
        $.ajax({
            url: "{{ route('lab.chemical.database.getDataByArr') }}",
            method: "get",
            data: {
                data: data
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                $('#modal-body-update').html(data.div);
                $('#adhesive_kind_inp').attr('placeholder', 'old_data : ' + kind);
            },
            complete: function(data) {
                $("#nav-loading").hide();
            }
        });
    }

    function openModalAddDatabase() {
        $('#modal_add_database').modal('show');
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
