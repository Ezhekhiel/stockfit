<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('dist/js/Chart.js') }}"></script>
<script>
    var role = {!! json_encode($role) !!};
    var any = {!! json_encode($errors->any()) !!};
    var alert_err = {!! json_encode($errors->first()) !!};
    $(document).ready(function(){
        if (any!="") {
            alert(alert_err);
        }
        var pengawas_pertama = $('#id_pilih_pengawas').val();
        var shift_pertama = $('#id_data_shift').val();
        var date_pertama = $('#id_data_date').val();
        main(date_pertama, pengawas_pertama,shift_pertama)
        if (role==0) {
            var intervalMain = setInterval(function() {main( $('#id_data_date').val(), $('#id_pilih_pengawas').val(),$('#id_data_shift').val());}, 5000);
        }
        dataList();
        showDataPengawas();
        $('#form_input').on('submit', function(event){
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            shift_pertama = $('#id_data_shift').val();
            date_pertama = $('#id_data_date').val();
            $(".border-danger").attr("class",'form-control clickReset validation ');
            var incoming = $("input[name='qty_incoming[]']").map(function(){return $(this).val();}).get();
            var balance = $("input[name='qty_balance[]']").map(function(){return $(this).val();}).get();
            var status = validationInput(incoming, balance);
            $.ajax({
                url:"{{ route('output_stf.input.save') }}",
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
                        main(date_pertama,pengawas_pertama,shift_pertama);
                        updateCardBalance(data.po,data.cell,data.wide,data.shift,data.jam,data.pengawas,data.form);
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
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
        $('#form_output').on('submit', function(event){
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            shift_pertama = $('#id_data_shift').val();
            date_pertama = $('#id_data_date').val();
            $(".border-danger").attr("class",'form-control clickReset validation ');
            var incoming = $("input[name='qty_incoming[]']").map(function(){return $(this).val();}).get();
            var balance = $("input[name='qty_balance[]']").map(function(){return $(this).val();}).get();
            var status = validationInput(incoming, balance);
            $.ajax({
                url:"{{ route('output_stf.output.save') }}",
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
                        main(date_pertama,pengawas_pertama,shift_pertama);
                        updateCardBalance(data.po,data.cell,data.wide,data.shift,data.jam,data.line,data.form);
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
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
        $('#delete-modal-detailperline').on('submit',function (event) {
            event.preventDefault();
            $.ajax({
                url:"{{ route('output_stf.delete.byPerline') }}",
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
                        main(data.when,data.pengawas,data.shift);
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
                        openDetailPerLine(data.jam,data.when,data.pengawas,data.shift,data.form);
                    }else{
                        createAlert('','Gagal!',data.text,data.color,true,true,'pageMessages');
                    }
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
        $('#form_shortage_input').on('submit', function(event){
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            shift_pertama = $('#id_data_shift').val();
            date_pertama = $('#id_data_date').val();
            $(".border-danger").attr("class",'form-control clickReset validation ');
            var incoming = $("input[name='qty_incoming[]']").map(function(){return $(this).val();}).get();
            var balance = $("input[name='qty_balance[]']").map(function(){return $(this).val();}).get();
            var status = validationInput(incoming, balance);
            $.ajax({
                url:"{{ route('output_stf.shortage_input.save') }}",
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
                        main(date_pertama,pengawas_pertama,shift_pertama);
                        updateCardBalance(data.po,data.bm,data.wide,data.shift,data.jam,data.line,data.form);
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
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
        $('#form_shortage_output').on('submit', function(event){
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            shift_pertama = $('#id_data_shift').val();
            date_pertama = $('#id_data_date').val();
            $(".border-danger").attr("class",'form-control clickReset validation ');
            var incoming = $("input[name='qty_incoming[]']").map(function(){return $(this).val();}).get();
            var balance = $("input[name='qty_balance[]']").map(function(){return $(this).val();}).get();
            var status = validationInput(incoming, balance);
            $.ajax({
                url:"{{ route('output_stf.shortage_output.save') }}",
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
                        main(date_pertama,pengawas_pertama,shift_pertama);
                        updateCardBalance(data.po,data.bm,data.wide,data.shift,data.jam,data.line,data.form);
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
                         $('.validation').val('');

                    }else{
                        createAlert('','Gagal!',data.text,data.color,true,true,'pageMessages');
                    }
                },
                complete:function(){
                    // Hide image container
                    $("#nav-loading").hide();
                }
            })
        });
        $('#form_reject').on('submit', function(event){
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            shift_pertama = $('#id_data_shift').val();
            date_pertama = $('#id_data_date').val();
            $.ajax({
                 url:"{{ route('output_stf.reject.save') }}",
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
                        main(date_pertama,pengawas_pertama,shift_pertama);
                        createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
                        $('.validation').val('');
                    }else{
                        createAlert('','Gagal!',data.text,data.color,true,true,'pageMessages');
                    }
                },
                complete:function(){
                    // Hide image container
                    $("#nav-loading").hide();
                }
            })
        });
        $('#id_buymonth_input').on('change',function (event) {
            event.preventDefault();
            changeBM(this.value,'input');
        });
        $('#id_po_input').on('change',function (event) {
            event.preventDefault();
            changePO('input');
        })
        $('#id_po_output').on('change',function (event) {
            event.preventDefault();
            changePO('output');
        })
        $('#id_po_shortage_input').on('change',function (event) {
            event.preventDefault();
            changePO('shortage_input');
        })
        $('#id_po_shortage_output').on('change',function (event) {
            event.preventDefault();
            changePO('shortage_output');
        })
        $('#id_jam_input').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('input');
            }
        });
        $('#id_cell_input').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('input');
            }
        });
        $('#id_jam_output').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('output');
            }
        });
        $('#id_cell_output').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('output');
            }
        });
        $('#id_jam_shortage_input').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('shortage_input');
            }
        });
        $('#id_cell_shortage_input').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('shortage_input');
            }
        });
        $('#id_jam_shortage_output').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('shortage_output');
            }
        });
        $('#id_cell_shortage_output').on('change',function (event) {
            event.preventDefault();
            if (this.value!="") {
                searchDetailPO('shortage_output');
            }
        });
        $('.reset').on('click',function (event) {
            event.preventDefault();
            $('.reset').val('');
        });
        $('#flexRadioDefault2').on('click',function (event) {
            $('#display_all').hide();
            $('#display_perLine').show();
            $('#btn_pilih_line').show();
            $('#fg_pilih_line').show();
        });
        $('#flexRadioDefault1').on('click',function (event) {
            $('#display_perLine').hide();
            $('#fg_pilih_line').hide();
            $('#btn_pilih_line').hide();
            $('#display_all').show();
        });
        $('#id_pilih_pengawas').on('change',function (event) {
            event.preventDefault();
            shift_pertama = $('#id_data_shift').val();
            date_pertama = $('#id_data_date').val();
            main(date_pertama,this.value,shift_pertama);
        });
        $('.clickReset').on('click',function (event) {
            $(this).val('');
        })
        $('#id_data_shift').on('change',function (event) {
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            date_pertama = $('#id_data_date').val();
            main(date_pertama,pengawas_pertama,this.value);
        });
        $('#id_data_date').on('change',function (event) {
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            shift_pertama = $('#id_data_shift').val();
            main(this.value, pengawas_pertama, shift_pertama)
        });
        $(".validation").on('change',function (event) {
            event.preventDefault();
            if (this.value < 0) {
                $(this).val(0);
            }
        });
    });
    function checkAllDetailPerLine() {
        if ($('input.checkboxDetailPerline').is(':checked')) {
            $('.checkboxDetailPerline').prop('checked',false);
        }else{
            $('.checkboxDetailPerline').prop('checked',true);
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
    function dataPengawasUpdate(id){
        $.ajax({
            url:"{{ route('output_stf.getData.Pengawas') }}",
            method:"get",
            data:{id:id},
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#modal-update-pengawas').modal('show');
                $('#id-register-update').val(data.arr['id']);
                $('#nik-register-update').val(data.arr['nik']);
                $('#nama-register-update').val(data.arr['nama']);
            },
            complete:function(data){
                // Hide image container
                $("#nav-loading").hide();
            }
        });
    }
    function dataPengawasDelete(id){
        $.ajax({
            url:"{{ route('output_stf.delete.Pengawas') }}",
            method:"get",
            data:{id:id},
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                showDataPengawas();
            },
            complete:function(){
                // Hide image container
                $("#nav-loading").hide();
            }
        });
    }
    function showDataPengawas(){
        $.ajax({
            url:"{{ route('output_stf.data_pengawas') }}",
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#tbody_pengawas').html(data.tbody_pengawas);
                $('.select-pengawas').html(data.list_pengawas);
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        });
    }
    function openModalRegisterPengawas(){
        $('#modalTambahPengawas').modal('show');
    }
    function showDetail(date,id_pengawas,shift) {
        if ($(".trShowDetail")[0]){
            $('.trShowDetail').remove();
            $('#icon-'+id_pengawas).toggleClass('fa-minus fa-plus rotate');
        }else{
            $('#icon-'+id_pengawas).toggleClass('fa-plus fa-minus rotate');
            $.ajax({
                url:"{{ route('output_stf.showDetail') }}",
                method:"get",
                data:{date:date,id_pengawas:id_pengawas,shift:shift},
                dataType:'JSON',
                beforeSend: function(){
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    var stringTbodyInside = '<tr onclick="showDetail(\''+date+'\',\''+id_pengawas+'\',\''+shift+'\')" class="trShowDetail" id="demoTargetDefault-'+id_pengawas+'">'+
                                                '<td colspan="26">'+
                                                    '<div class="accordian-body" style="max-height:300px;overflow-y:scroll;">'+
                                                        '<table class="table table-hover">'+
                                                            '<thead>'+
                                                                '<tr class="info">'+
                                                                    '<th>SHIFT</th>'+
                                                                    '<th>LINE</th>'+
                                                                    '<th>JAM</th>'+
                                                                    '<th>CELL</th>'+
                                                                    '<th>PO</th>'+
                                                                    '<th>STYLE</th>'+
                                                                    '<th>SIZE</th>'+
                                                                    '<th>QTY</th>'+
                                                            ' </tr>'+
                                                            '</thead>'+
                                                            '<tbody>'+
                                                                data.tbody+
                                                            '</tbody>'+
                                                            '<tfoot>'+
                                                                data.tfoot+
                                                            '</tfoot>'+
                                                        '</table>'+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>';
                    $('#trID-'+id_pengawas).after(stringTbodyInside);
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            });
        }
    }
    function resetInput() {
        $('reset').val('');
    }
    function main(when,pengawas,shift) {
        $.ajax({
            url:"{{ route('output_stf.main') }}",
            method:"get",
            data:{when:when,pengawas:pengawas,shift:shift},
            dataType:'JSON',
            beforeSend: function(){
                // Show image container
                $("#nav-loading").show();
            },
            success:function(data)
            {
                if (data.err) {
                    $('#tb_perCell').html('');
                    $('#tb_data').html('');
                    if (role!=0) {
                        createAlert('','Gagal!',data.err,data.color,true,true,'pageMessages');
                    }
                }else{
                    $('#tb_data').html(data.tb_data);
                    $('#tb_perCell').html(data.tb_perCell);
                    $('#buymonth_list').html(data.bm_list);
                }
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        });
    }
    function openWIP(){
        var shift = $('#id_data_shift').val();
        var pengawas = $('#id_pilih_pengawas').val();
        var form = "Data WIP";

        $('#jam-modal-detail-perline').html('-');
        $('#date-modal-detail-perline').html('-');
        $('#shift-modal-detail-perline').html(shift);
        $('#form-modal-detail-perline').val(form);
        $('#th-modal-detail-perline').html(form.toUpperCase());
        $('#title-modal-detail-perLine').html(" ("+form.toUpperCase()+") ");
        $('#modal-show-data-detail-perline').modal('show');

        $('#jam-modal-input-perLine').val('-');
        $('#date-modal-input-perLine').val('-');
        $('#pengawas-modal-input-perLine').val(pengawas);
        $('#shift-modal-input-perLine').val(shift);

        $.ajax({
            url:"{{ route('output_stf.getData.wip') }}",
            method:"get",
            data:{pengawas:pengawas,shift:shift},
            dataType:'JSON',
            beforeSend: function(){
                // Show image container
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#detail-perline').html(data.table);
                $('#pengawas-modal-detail-perline').html(data.nama_pengawas);
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        });
    }
    function openDetailPerDay(){
        var when = $('#id_data_date').val();
        var shift = $('#id_data_shift').val();
        var pengawas = $('#id_pilih_pengawas').val();
        var form = "Per Day";

        $('#jam-modal-detail-perline').html('-');
        $('#date-modal-detail-perline').html(when);
        $('#shift-modal-detail-perline').html(shift);
        $('#form-modal-detail-perline').val(form);
        $('#th-modal-detail-perline').html(form.toUpperCase());
        $('#title-modal-detail-perLine').html(" ("+form.toUpperCase()+") ");
        $('#modal-show-data-detail-perline').modal('show');

        $('#jam-modal-input-perLine').val('-');
        $('#date-modal-input-perLine').val(when);
        $('#pengawas-modal-input-perLine').val(pengawas);
        $('#shift-modal-input-perLine').val(shift);

        $.ajax({
            url:"{{ route('output_stf.getData.detail_gabungan') }}",
            method:"get",
            data:{when:when,pengawas:pengawas,shift:shift,form:form},
            dataType:'JSON',
            beforeSend: function(){
                // Show image container
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#detail-perline').html(data.table);
                $('#pengawas-modal-detail-perline').html(data.nama_pengawas);
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        });
    }
    function openDetailPerLine(jam,when,pengawas,shift,form){
        $('#jam-modal-detail-perline').html(jam);
        $('#date-modal-detail-perline').html(when);
        $('#shift-modal-detail-perline').html(shift);
        $('#form-modal-detail-perline').val(form);
        $('#th-modal-detail-perline').html(form.toUpperCase());
        $('#title-modal-detail-perLine').html(" ("+form.toUpperCase()+") ");
        $('#modal-show-data-detail-perline').modal('show');

        $('#jam-modal-input-perLine').val(jam);
        $('#date-modal-input-perLine').val(when);
        $('#pengawas-modal-input-perLine').val(pengawas);
        $('#shift-modal-input-perLine').val(shift);
        if (form == "Match Per Hour") {
            var url="{{ route('output_stf.getData.detail_gabungan') }}";

        }else{
            var url="{{ route('output_stf.getData.detail_perline') }}";
        }
        $.ajax({
            url : url,
            method:"get",
            data:{jam:jam,when:when,pengawas:pengawas,shift:shift,form:form},
            dataType:'JSON',
            beforeSend: function(){
                // Show image container
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#detail-perline').html(data.table);
                $('#pengawas-modal-detail-perline').html(data.nama_pengawas);
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        });
    }
    function dataList() {
        $.ajax({
            url:"{{ route('output_stf.getPo_list') }}",
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
                $('#output_shortage_po_list').html(data.output_shortage_po_list);
            },
            complete:function(){
                $("#nav-loading").hide();
            }
        });
    }
    function showData() {
        $('.nav-link').attr('class','nav-link');
        $('.tab-pane').attr('class','tab-pane');
        $("#id_data_shift").prop('selectedIndex', 0);
        $("#id_pilih_pengawas").prop('selectedIndex', 0);
        main( $('#id_data_date').val(), $('#id_pilih_pengawas').val(),$('#id_data_shift').val());
    }
    function changePO(form) {
        $('.validation_balance').attr('readonly',true);
        $('.validation_balance').val('');
        $('.validation').attr('readonly',false);
        $('.validation').val('');
        $('.detail_po').attr('readonly',true);
        $('.detail_po').val('');
        var no = 0;
        for (let i = 0; i < 30; i++) {
            no++;
            $('#label_size_'+form+'_'+no).html(no);
        }
        $("#id_jam_"+form).val($("#id_jam_input option:first").val());
    }
    function changeBM(bm,option) {
        $.ajax({
                url:"{{ route('output_stf.change_bm') }}",
                method:"get",
                data:{bm:bm},
                dataType:'JSON',
                beforeSend: function(){
                    // Show image container
                    $("#nav-loading").show();
                },
                success:function(data)
                {
                    if ($('#id_po_'+option).val()=="") {
                        $('#po_list').html(data.list_po);
                    }
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
    }
    function updateCardBalance(po,cell,wide,shift,jam,pengawas,form) {
        $.ajax({
            url:"{{ route('output_stf.change.data') }}",
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
    function validationInput(incoming,balance) {
        var arrData =[];
        for (let i = 0; i < incoming.length; i++) {
            arrData=parseInt(incoming[i])-parseInt(balance[i]);
            if (arrData>0) {

                return i+1;
            }
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
    function chartBTS(label,data) {
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [{
                label: '#BTS %',
                data: data,
                backgroundColor:'rgba(196,215,155,255)',
                borderColor:'rgba(220,228,228)',
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
                        callback: function(value){return value+ "%"}
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
<script>

</script>
