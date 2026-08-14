<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $(document).ready(function(){
        main();
        $('#addBarcode').on('click',function(event) {
            $('#addBarcode_modal').modal('show');
        });
        $('#form-print-modal').on('submit',function (event) {
            main();
        });
        $('#search_data').on('change',function(event){
            var link = $('#search_data').val();

            if (link.slice(0,5)=='http:') {
                var id = link.split("/")[6];
                $('#search_data').val(id);
                main(id,'');
            }else{
                main($(this).val(),'');
            }
            $('#search_data').val('');
        });
        $('#search_data_model').on('keyup',function(event) {
            event.preventDefault();
            main($(this).val(),'model');
        })
        $('.option_update').on('click',function(){
            $('.div_update').show();
        });
        $('#cancle_id').on('click',function(event) {
            $('.div_update').hide();
        });
        $('#id_location_scan').on('change',function (event) {
            if ($(this).find(":selected").val() == 'WAREHOUSE') {
                $('#id_status_scan').prop('selectedIndex',3);
                $('#tr_no_rack').show();
            }else{
                $('#tr_no_rack').hide();
            }
        })
        $('#form-update-main').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('tooling.pad_press_stockfit.update') }}",
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(){
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    if(data.alert=="success")
                    {
                        $('.form-update').val();
                        createAlert('','Success!',data.text,data.color,true,true,'pageMessages');
                        main();
                        $('#reason_option').prop('selectedIndex',0);
                        $('.div_update').hide();
                    }else{
                        createAlert('','Gagal!',data.text,data.color,true,true,'pageMessages');
                    }
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
    });
    function deleteModal(id_barcode) {
        var model = $('#search_data_model').val();
        if (confirm("Are you sure ?") == true) {
            $.ajax({
                url:"{{ route('tooling.pad_press_stockfit.delete') }}",
                method:"get",
                data:{id_barcode:id_barcode},
                dataType:'JSON',
                beforeSend: function(){
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    createAlert('','Success!',data.text,data.color,true,true,'pageMessages');
                    main('',model);
                },
                complete:function(data){
                    $("#nav-loading").hide();
                }
            });
        } else {
            alert("Canceled!");
        }
    }
    function main(where,model){

        $.ajax({
            url:"{{ route('tooling.pad_press_stockfit.main') }}",
            method:"get",
            data:{where:where,model:model},
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#tbody').html(data.table);
            },
            complete:function(data){
                $("#nav-loading").hide();
            }
        });
    }
    function updateModal(id_barcode) {
        $('#updateData').modal('show');
        $.ajax({
            url:"{{ route('tooling.pad_press_stockfit.scan_main') }}",
            method:"get",
            data:{id:id_barcode},
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#id_data').val(id_barcode);
                $('#id_barcode_scan').html(data.arr_table['id_barcode']);
                $('#id_model_scan').html(data.model_option);
                $('#id_gender_scan').html(data.gender_option);
                $('#id_size_scan').html(data.size_option);
                $('#id_side_scan').html(data.side_option);
                $('#id_remark_option').html(data.remark_option);
                $('#id_version_option').html(data.version_option);
                $('#id_location_scan').html(data.location_option);
                $('#id_status_scan').html(data.status_option);
                $('#no_rack_option').html(data.no_rack_option);
                $('#table-history').html(data.table_history);
                if (data.status =='WAREHOUSE') {
                    $('#tr_no_rack').show();
                }else{
                    $('#tr_no_rack').hide();
                }
            },
            complete:function(data){
                $("#nav-loading").hide();
            }
        });
    }
    function showRackMap(id_rack) {
        $('#showRack').modal('show');
        $('.rack').attr('class','col-6 rack');
        $('#'+id_rack).addClass('bg-danger');
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
