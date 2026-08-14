<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('dist/js/Chart.js') }}"></script>
<script>
    $(document).ready(function(){
        var when = 'now';
        var shift = '';
        main(when,shift);
        var interval = setInterval(function() {main(when,shift)}, 15000);
        $('#id_shift_input').on('change',function(event) {
            event.preventDefault();
            var date = $('#id_date').val();
            var shift = $(this).val();
            clearInterval(interval);
            main(date, $(this).val());
            interval = setInterval(function() {main(date, shift)}, 15000);
        });
        $('#id_date').on('change',function(event) {
            event.preventDefault();
            var shift = $('#id_shift_input').val();
            var date = $(this).val();
            if (shift=="Pilih Shift") {
                shift="";
            }
            clearInterval(interval);
            main(date, shift);
            interval = setInterval(function() {main(date, shift)}, 15000);
        });
        $('#tbody_dashboard').on('click','.circle',function(event) {
            $('#tbody_list_spk').html('');
            event.preventDefault();
            $('#modalInfo').modal('show');
            var shift = $('#id_shift_input').val();
            var date = $('#id_date').val();
            $.ajax({
                url:"{{ route('setting_line.dashboard.show_modal') }}",
                method:"get",
                data:{id:this.id,date:date,shift:shift},
                dataType:'JSON',
                beforeSend: function(){
                    // Show image container
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    $('#tbody_list_spk').html(data.table);
                },
                complete:function(data){
                    // Hide image container
                    $("#nav-loading").hide();
                }
            })
        });
        $('#showCollaps').on('click',function(){
            var isi =$('#showCollaps').html();
            if (isi=="Show") {
                $('#showCollaps').html("Hide");
            }else{
                $('#showCollaps').html("Show");
            }
        })
        $('#data-tab').on('click',function(event) {
            event.preventDefault();
            clearInterval(interval);
            dateTabFunction("","","","","","","","","","");
        })
        $('#date_form_data').on('change',function(evemt) {
            event.preventDefault();
            dateTabFunction($(this).val(),"","","","","","","","","");
        })
        $('.search').on('change',function(evemt) {
            evemt.preventDefault();
            var date = $('#date_form_data').val();
            var shift = $('#search_shift').val();
            var pengawas = $('#search_pengawas').val();
            var line = $('#search_line').val();
            var jam = $('#search_jam').val();
            var po = $('#search_po').val();
            var wide = $('#search_wide').val();
            var qty_order = $('#search_qty_order').val();
            var size_name = $('#search_size_name').val();
            var qty = $('#search_qty').val();
            var status = $('#search_status').val();
            dateTabFunction(date,shift,pengawas,line,jam,po,wide,qty_order,size_name,qty,status);
        })
    });
    function dateTabFunction(date,shift,pengawas,line,jam,po,wide,qty_order,size_name,qty,status) {
        $.ajax({
                url:"{{ route('setting_line.data') }}",
                method:"get",
                dataType:'JSON',
                data:{date:date,shift:shift,pengawas:pengawas,line:line,jam:jam,po:po,wide:wide,qty_order:qty_order,size_name:size_name,qty:qty,status:status},
                beforeSend: function(){
                    // Show image container
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    $('#tbodyData').html(data.table);
                    $('#shift_list').html(data.shift_list);
                    $('#pengawas_list').html(data.pengawas_list);
                    $('#line_list').html(data.line_list);
                    $('#jam_list').html(data.jam_list);
                    $('#po_list').html(data.po_list);
                    $('#wide_list').html(data.wide_list);
                    $('#qty_order_list').html(data.qty_order_list);
                    $('#size_name_list').html(data.size_name_list);
                    $('#qty_list').html(data.qty_list);
                    $('#status_list').html(data.status_list);
                },
                complete:function(data){
                    // Hide image container
                    $("#nav-loading").hide();
                }
            })
    }
    function main(when,shift) {
        $.ajax({
            url:"{{ route('setting_line.dashboard.main') }}",
            method:"get",
            data:{when:when,shift:shift},
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                if (data.alert=="Tidak ada") {
                    $('#table_head_pengawas_id').html(data.th);
                    $('#header_pengawas').attr('colspan',0);
                    $('#tbody_dashboard').html("");
                }
                if (data.th) {
                    $('#table_head_pengawas_id').html(data.th);
                    $('#header_pengawas').attr('colspan',data.colspan);
                    $('#tbody_dashboard').html(data.tbody);
                }
                // for (let i = 0; i < arrayResult.length; i++) {
                //     $('#'+arrayResult[i]['jam']+'_'+arrayResult[i]['cell']).addClass(arrayResult[i]['color']);
                // }
            },
            complete:function(){
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

            if(!iconAdded){
            msgTitle.prepend(msgIcon);
            iconAdded = true;
            }
        }

        if (summary) {
            var msgSummary = $("<strong />", {
            html: summary
            }).appendTo(msg);

            if(!iconAdded){
            msgSummary.prepend(msgIcon);
            iconAdded = true;
            }
        }

        if (details) {
            var msgDetails = $("<p />", {
            html: details
            }).appendTo(msg);

            if(!iconAdded){
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

        if(autoDismiss){
            setTimeout(function(){
            msg.addClass("flipOutX");
            setTimeout(function(){
                msg.remove();
            },1000);
            }, 5000);
        }
    }
</script>
