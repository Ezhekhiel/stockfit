<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('dist/js/Chart.js') }}"></script>
<script>
    $(document).ready(function(){
        dataList();
        main();
        $('#id_jam_input').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('input');
            }
        });
        $('#id_pengawas_input').on('change',function(event){
            event.preventDefault();
            change_progress_bar_input();
        });
        $('#input-tab').on('click',function(event){
            event.preventDefault();
            $('.form-control').val('');
            $('.form-control').prop('selectedIndex',0);
            $('.validation').val('');
            $('.validation_balance').val('');
            $('.validation').attr('readonly',false);
            $('.validation_balance').attr('readonly',true);
            mappingLine("Tidak ada");
        });
        $('#output-tab').on('click',function(event){
            event.preventDefault();
            $('.form-control').val('');
            $('.form-control').prop('selectedIndex',0);
            $('#detailLineOutput').html('');
            mappingLine("Tidak ada");
        });
        $('#id_cell_input').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('input');
            }
        });
        $('#form_input').on('submit', function(event){
            event.preventDefault();
            jam = $('#id_jam_input').val();
            status = $('#form_jam_'+jam).val();
            if (status == 'TRANSFER' ) {
                createAlert('','Gagal!','Data yang telah di transfer tidak boleh ada INPUT!','danger',true,true,'pageMessages');
                return 0;
            }
            $(".border-danger").attr("class",'form-control clickReset validation ');
            $.ajax({
                url:"{{ route('setting_line.input.save') }}",
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
                    if(data.alert=="sukses")
                    {
                        $('#modal_cell_target').modal('hide');
                        updateCardBalance(data.po,data.cell,data.wide,data.shift,data.jam,data.pengawas,data.form);
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
                        change_progress_bar_input();
                        $('.validation').val('');
                    }else{
                        createAlert('','Gagal!',data.text,data.color,true,true,'pageMessages');
                    }
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
        $('#form-update-modal').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('setting_line.update') }}",
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
                    if(data.alert=="Sukses!")
                    {
                        $('#modalUpdate').modal('hide');
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
                        change_progress_bar_output();
                    }else{
                        createAlert('','Gagal!',data.text,data.color,true,true,'pageMessages');
                    }
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
        $('#id_date_output').on('change',function(event){
            event.preventDefault();
            change_progress_bar_output();
        });
        $('#id_po_input').on('change',function(event){
            $('.detail_po').val('');
            $('#id_jam_input').prop('selectedIndex',0);
            $('.validation').val('').attr('readonly',false).removeAttr( 'style' );
            $('.validation_balance').val('').attr('readonly',true).attr('style','color: rgb(0, 0, 0);');
        })
        $('#id_pengawas_output').on('change',function(event){
            event.preventDefault();
            change_progress_bar_output();
        });
        $('.clickReset').on('click',function (event) {
            $(this).val('');
        })
    });
    function change_progress_bar_input(){
        var shift = $('#id_shift_input').val();
        var pengawas = $('#id_pengawas_input').val();
        var date = $('#id_date_input').val();
        if (shift=="Pilih Shift" && pengawas == "Pilih Pengawas" && line == "Pilih Line") {
            createAlert('','Gagal!','Data Shift / Pengawas Harus Dipilih','danger',true,true,'pageMessages');
            return 0;
        }
        $.ajax({
            url:"{{ route('setting_line.change_progress_bar') }}",
            data:{shift:shift,pengawas:pengawas,date:date},
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                mappingLine(data.arrayMappingCell);
            },
            complete:function(){
                // Hide image container
                $("#nav-loading").hide();
            }
        });
    }
    function change_progress_bar_output(){
        var shift = $('#id_shift_output').val();
        var pengawas = $('#id_pengawas_output').val();
        // var line = $('#id_line_output').val();
        var date = $('#id_date_output').val();
        if (shift=="Pilih Shift" && pengawas == "Pilih Pengawas") {
            createAlert('','Gagal!','Data Shift / Pengawas Harus Dipilih','danger',true,true,'pageMessages');
            return 0;
        }
        $.ajax({
            url:"{{ route('setting_line.change_progress_bar') }}",
            data:{shift:shift,pengawas:pengawas,date:date},
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                if (data.arrayMappingCell=='Tidak ada') {
                    $('#detailLineOutput').html("");
                }else{
                    $('#detailLineOutput').html(data.table);
                }
                mappingLine(data.arrayMappingCell);
            },
            complete:function(){
                // Hide image container
                $("#nav-loading").hide();
            }
        });
    }
    function mappingLine(arrMpCell){
        //reset progressbar
            $('.progress-bar').css('width','0');
            $('.progress-bar').html('0');
            $('.progress-bar').attr('class','progress-bar progress-bar-striped progress-bar-animated');
            $('.display-progress-bar').html('');
        if (arrMpCell !== "Tidak ada") {
            for (let i = 0; i < arrMpCell['id'].length; i++) {
                $('#jam_'+arrMpCell['id'][i]).css('width',arrMpCell['width'][i]);
                $('#jam_'+arrMpCell['id'][i]).html(arrMpCell['width'][i]);
                $('#display_jam_'+arrMpCell['id'][i]).html(arrMpCell['display'][i]);
                $('#form_jam_'+arrMpCell['id'][i]).val(arrMpCell['status'][i]);
                $('#jam_'+arrMpCell['id'][i]).attr('class','progress-bar progress-bar-striped pb-2 progress-bar-animated '+arrMpCell['color'][i]);
            }
        }else{
            createAlert('','Gagal!','Data Tidak Ada','danger',true,true,'pageMessages');
        }
    }
    function validationInput(incoming,balance) {
        var arrData =[];
        for (let i = 0; i < incoming.length; i++) {
            arrData=parseInt(incoming[i])-parseInt(balance[i]);
            if (arrData>0) {

                return i+1;
            }
        }
    }
    function functionDetail(shift,pengawas,date,jam) {
        var statusShow = $('#statusShow').val();
        if (statusShow==0) {
            $.ajax({
                url:"{{ route('setting_line.detail_line') }}",
                method:"get",
                data:{shift:shift,pengawas:pengawas,date:date,jam:jam},
                dataType:'JSON',
                beforeSend: function(){
                    // Show image container
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    $('#trAwal'+jam).after(data.table);
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            });
            $('#statusShow').val('1');
        }else{
            $('.trDetail').remove();
            $('#statusShow').val(0);
        }

    }
    function main() {
        $.ajax({
            url:"{{ route('setting_line.main') }}",
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                // Show image container
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#id_pengawas_input').html(data.pengawas_list);
                $('#id_pengawas_output').html(data.pengawas_list);
                $('#list_id_pengawas_modal').html(data.pengawas_list);
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        });
    }
    function functionTransfer(pengawas,shift,date,jam,status){
        if (status == 'TRANSFER') {
            createAlert('','Gagal!','Data yang telah di transfer!','danger',true,true,'pageMessages');
            return 0;
        }else if(status == "TIDAK ADA BARANG"){
            createAlert('','Gagal!','Tidak ada barang di jam ini!','danger',true,true,'pageMessages');
            return 0;
        }else if(status == 'TARGET BELUM SETTING'){
            createAlert('','Gagal!','Setting target dahulu sebelum transfer','danger',true,true,'pageMessages');
            return 0;
        }
        if (confirm("Yakin akan transfer data ini?") == true) {
            $.ajax({
                url:"{{ route('setting_line.transfer') }}",
                method:"get",
                data:{pengawas:pengawas,shift:shift,date:date,jam:jam,status:status},
                dataType:'JSON',
                beforeSend: function(){
                    // Show image container
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                    change_progress_bar_output();
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            });
        } else {
            alert("Batal!")
        }
    }
    function functionModalUpdate(pengawas,shift,date,jam,status,nama_pengawas){
        if (status == 'TRANSFER' || status == "TRANSFER TIDAK SESUAI TARGET") {
            createAlert('','Gagal!','Data yang telah di transfer tidak bisa di Update','danger',true,true,'pageMessages');
            return 0;
        }
        $('.form-update').prop('selectedIndex',0);
        $('.form-update').val('');
        $('#modalUpdate').modal('show');
        $('#id_nama_pengawas_modal').val(nama_pengawas);
        $('#id_nik_pengawas_modal').val(pengawas);
        $('#id_shift_modal').val(shift);
        $('#id_date_modal').val(date);
        $('#id_jam_modal').val(jam);
        // $('#id_line_modal').val(line);
    }

    function functionModalDelete(pengawas,shift,date,jam,status){
        var role = {!! json_encode($role) !!};
        if (role != '1' && role != '4' && role !=  '7') {
            if (status == 'TRANSFER') {
                createAlert('','Gagal!','Data yang telah di transfer tidak bisa di hapus','danger',true,true,'pageMessages');
                return 0;
            }
        }
        if (confirm("Yakin akan hapus data ini?") == true) {
            $.ajax({
                url:"{{ route('setting_line.delete_input') }}",
                method:"get",
                data:{pengawas:pengawas,shift:shift,date:date,jam:jam,status:status},
                dataType:'JSON',
                beforeSend: function(){
                    // Show image container
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                    if (data.alert=="Sukses!") {
                        setTimeout(function() {
                            window.location.href = window.location;
                        }, 5000);
                    }
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            });
        } else {
            alert("Batal!")
        }
    }
    function functionModalDeleteDetail(id,input,output){
        if (input <= output) {
            createAlert('','Gagal!','Data yang telah di transfer tidak bisa di hapus','danger',true,true,'pageMessages');
            return 0;
        }
        if (confirm("Yakin akan hapus data ini?") == true) {
            $.ajax({
                url:"{{ route('setting_line.delete_input.detail') }}",
                method:"get",
                data:{id:id},
                dataType:'JSON',
                beforeSend: function(){
                    // Show image container
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                    if (data.alert=="Sukses!") {
                        setTimeout(function() {
                            window.location.href = window.location;
                        }, 5000);
                    }
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            });
        } else {
            alert("Batal!")
        }
    }
    function searchDetailPO(form) {
        var po = $('#id_po_'+form).val();
        var cell = $('#id_cell_'+form).val();
        var wide = $('#id_wide_'+form).val();
        var shift = $('#id_shift_'+form).val();
        var jam = $('#id_jam_'+form).val();
        var pengawas = $('#id_pengawas_'+form).val();
        //disale input wide
            $('#id_wide_'+form).attr('readonly',true);
            $('#id_cell_'+form).attr('readonly',true);
        //reset validation / size incoming
            $('.validation_balance').val('');
        updateCardBalance(po,cell,wide,shift,jam,pengawas,form);
    }
    function updateCardBalance(po,cell,wide,shift,jam,pengawas,form) {
        $.ajax({
            url:"{{ route('setting_line.change_data') }}",
            method:"get",
            data:{po:po,cell:cell,wide:wide,shift:shift,jam:jam,pengawas:pengawas,form:form},
            dataType:'JSON',
            beforeSend: function(){
                // Show image container
                $("#nav-loading").show();
            },
            success:function(data)
            {
                if (data.count_data>1) {
                    $('#id_wide_'+data.form).attr('readonly',false);
                    $('#id_cell_'+data.form).attr('readonly',false);
                    $('#wide_list_'+data.form).html(data.wide_list);
                    $('#cell_list_'+data.form).html(data.cell_list);
                }else{
                    $('#id_wide_'+data.form).val(data.wide);
                    $('#id_cell_'+data.form).val(data.cell);
                    $('#id_style_'+data.form).val(data.style);
                    $('#id_qty_po_'+data.form).val(data.qty);
                    $('#id_gender_'+data.form).val(data.gender);
                    $('.validation_balance').val('');
                    for (let i = 0; i < data.size.length; i++) {
                        var ke = i+1;
                        if (data.data_qty[i]==="") {
                            $('#size_'+data.form+'_'+ke).attr('readonly',true);
                            $('#label_balance_'+ke).html(data.size[i]);
                        }else{
                            $('#size_'+ke).attr('readonly',false);
                            $('#label_size_'+form+'_'+ke).html("Size - "+data.size[i]);
                            $('#label_balance_'+form+'_'+ke).html("Size - "+data.size[i]);
                        }
                    }
                    for (let i = 0; i < data.data_qty.length; i++) {
                        var ke = i+1;
                        if (data.data_qty[i]==="") {
                            $('#balance_'+data.form+'_'+ke).val('');
                            $('#visual_'+data.form+'_'+ke).css({'background-color':'',color:'black'});
                        }else{
                            if (data.data_qty[i]==data.balance_qty[i]) {
                                var color = 'white';
                                var font = 'black';
                            }else if(data.data_qty[i]==0){
                                var color = 'green';
                                var font = 'white';
                            }else if(data.data_qty[i]<0){
                                var color = 'red';
                                var font = 'white';
                            }else{
                                var color = 'yellow';
                                var font = 'black';
                            }
                            $('#balance_'+data.form+'_'+ke).val(data.data_qty[i]);
                            $('#visual_'+data.form+'_'+ke).val(data.arrVisual[i]);
                            $('#visual_'+data.form+'_'+ke).css({'background-color':color,color:font});
                        }
                    }
                }
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        })
    }
    function dataList() {
        $.ajax({
            url:"{{ route('setting_line.getPo_list') }}",
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                // Show image container
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#po_list').html(data.po_list);
                $('#output_po_list').html(data.output_po_list);
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
