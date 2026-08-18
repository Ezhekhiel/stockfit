<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"
    integrity="sha512-+NqPlbbtM1QqiK8ZAo4Yrj2c4lNQoGv8P79DPtKzj++l5jnN39rHA/xsqn8zE9l0uSoxaCdrOgFs6yjyfbBxSg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        $('#btnToggleShowAct').on('click', function(e) {
            e.preventDefault();

            // Animasi slideDown / slideUp ala collapse
            $('#showActContainer').slideToggle(300, function() {
                // Ubah teks tombol sesuai status terbuka/tertutup
                if ($(this).is(':visible')) {
                    $('#btnToggleShowAct').html(
                        '<i class="bi bi-eye-slash"></i>');
                } else {
                    $('#btnToggleShowAct').html(
                        '<i class="bi bi-eye"></i>');
                }
            });
        });

    });
</script>
<script>
    var arrDatabase = [];
    const isLoggedIn = @json(auth()->check());

    const database = @json($database);
    let data_waste = {};
    let oldValue = {};
    const today = new Date();

    const picker = flatpickr(".date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [today, today],

        onClose: function(selectedDates) {
            if (selectedDates.length === 2) {

                const startDate = selectedDates[0];
                const endDate = selectedDates[1];

            }
        }
    });

    $(document).ready(function() {
        searchChemical();
        var area = $("#select_option").val();
        var date_range = $("#search_date_range").val();
        main(area, date_range);
        window.Echo.channel('chemical-waste')
            .listen('.chemical.created', function(e) {

                console.log('Chemical baru:', e.area);

                // Ubah dropdown sesuai area chemical baru
                $('#select_option').val(e.area);

                // Refresh dashboard berdasarkan area tersebut
                main(e.area, date_range);

            });
        setInterval(function() {

            var area = $("#select_option").val();
            var date_range = $("#search_date_range").val();
            main(area, date_range);

        }, 300000);

        $('#form-input-modal').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('lab.chemical.waste') }}",
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
                        $('#input_modal').modal('hide');
                    }
                    createAlert('', data.alert, data.text, data.color, true, true,
                        'pageMessages');
                },
                complete: function() {
                    $("#nav-loading").hide();
                }
            });
        });
        $('#form-update-modal').on('submit', function(event) {

            var id = $('#id_update').val();

            event.preventDefault();

            $.ajax({
                url: `/lab/chemical_waste/${id}`,
                method: 'PUT',

                data: {
                    gram: $('#id_update_gram').val(),
                    description: $('#id_update_description').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                beforeSend: function() {
                    $("#nav-loading").show();
                },

                success: function(data) {

                    if (data.alert == "Sukses!") {

                        // Hilangkan focus dari button submit
                        document.activeElement.blur();

                        // Tutup modal
                        $('#update_modal').modal('hide');
                    }

                    createAlert(
                        '',
                        data.alert,
                        data.text,
                        data.color,
                        true,
                        true,
                        'pageMessages'
                    );
                },

                complete: function() {
                    $("#nav-loading").hide();
                },

                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });

        });
        $('#inputButton').on('click', function(event) {
            document.getElementById("form-input-modal").reset();
            $('#search_data').val("");
            $('#input_modal').modal('show');
        });
        $('#exportButton').on('click', function() {
            $('#export_modal').modal('show');
        });
        $(document).on('click', '.btn-update', function(event) {

            const id = Number(this.dataset.id);

            const cari_data = data_waste.find(item => item.id === id);

            $('#update_modal').modal('show');
            $('#id_update_code_chemical').val(cari_data.chemical.code_chemical);
            $('#id_update_model').val(cari_data.chemical.model);
            $('#id_update_lot_number').val(cari_data.lot_number);
            $('#id_update_gram').val(cari_data.gram);
            $('#id_update_description').val(cari_data.description);
            $('#id_update').val(id);
            $('#btn-delete-update').data('id', id);

        });
        $(document).on('click', '#btn-delete-update', function() {

            const id = $(this).data('id');

            deleteData(id);
        });
        $('#search_data').on('keyup', function(event) {
            searchChemical();
        });
        $("#btnExport").on('click', function(event) {
            event.preventDefault();

            const dates = picker.selectedDates;
            const area = $('#select_area_export').val();

            console.log(dates);

            if (dates.length !== 2) {
                alert('Silakan pilih range tanggal terlebih dahulu.');
                return;
            }

            const startDate = picker.formatDate(dates[0], "Y-m-d");
            const endDate = picker.formatDate(dates[1], "Y-m-d");

            console.log('Start Date:', startDate);
            console.log('End Date:', endDate);

            const url =
                `/lab/chemical_waste/export?area=${area}&start_date=${startDate}&end_date=${endDate}`;

            window.location.href = url;
        });
    });

    function onchangeMain() {
        var area = $("#select_option").val();
        var date = $("#search_date_range").val();
        main(area, date)
    }

    function deleteData(id) {
        $.ajax({
            url: `/lab/chemical_waste/${id}`,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                if (data.alert == "Sukses!") {

                    // Hilangkan focus dari button submit
                    document.activeElement.blur();

                    // Tutup modal
                    $('#update_modal').modal('hide');
                }

                createAlert(
                    '',
                    data.alert,
                    data.text,
                    data.color,
                    true,
                    true,
                    'pageMessages'
                );
            },
            complete: function() {
                $("#nav-loading").hide();
            },
            error: function(xhr) {
                console.error('Gagal mengambil data:', xhr.responseText);
            }
        });
    }

    function main(area, date_range) {
        $.ajax({
            url: "{{ route('lab.chemical.waste.main') }}",
            method: "get",
            data: {
                area: area,
                date_range: date_range
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#nav-loading").show();
            },
            success: function(data) {
                renderTable(data);
                data_waste = data;
            },
            complete: function() {
                $("#nav-loading").hide();
            },
            error: function(xhr) {
                console.error('Gagal mengambil data:', xhr.responseText);
            }
        });

    }

    function renderTable(data) {
        const tbody = document.getElementById('tbody');
        let no = 1;

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>${no++}</td>
                <td>${item.chemical?.code_chemical ?? '-'}</td>
                <td>${item.chemical?.model ?? '-'}</td>
                <td>${item.chemical?.supplier ?? '-'}</td>
                <td>${item.chemical?.type ?? '-'}</td>
                <td>${item.chemical?.adhesive_kind ?? '-'}</td>
                <td>${item.gram ?? '-'}</td>
                <td>${item.lot_number ?? '-'}</td>
                <td>
                    ${isLoggedIn ? `
                        <button type="button"
                            class="btn btn-sm btn-warning btn-update"
                            data-id="${item.id}">
                            Update
                        </button>
                    ` : ''}
                </td>
            </tr>
        `).join('');
    }

    function renderChemicalTable(data) {
        const tbody = document.getElementById('tbody_database');
        tbody.innerHTML = data.map(item => `
            <tr>
                 <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="id_chemical" value="${item.id ?? '-'}">
                    </div>
                </td>
                <td>${item.code_chemical ?? '-'}</td>
                <td>${item.model ?? '-'}</td>
                <td>${item.supplier ?? '-'}</td>
                <td>${item.component ?? '-'}</td>
                <td>${item.type ?? '-'}</td>
            </tr>
        `).join('');
    }

    function searchChemical() {

        const keyword = document
            .getElementById('search_data')
            .value
            .toLowerCase()
            .trim();
        const filtered = database.filter(item =>
            (item.code_chemical ?? '').toLowerCase().includes(keyword) ||
            (item.model ?? '').toLowerCase().includes(keyword)
        );

        renderChemicalTable(filtered);
    }

    function searchMain() {

        const keyword = document
            .getElementById('search_id')
            .value
            .toLowerCase()
            .trim();

        const filtered = data_waste.filter(item =>
            (item.chemical.code_chemical ?? '').toLowerCase().includes(keyword) ||
            (item.chemical.model ?? '').toLowerCase().includes(keyword)
        );

        renderTable(filtered);
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
