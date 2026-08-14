<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('dist/js/Chart.js') }}"></script>
<script>
    $(document).ready(function(){
        main();
        $('#save-target').on('submit', function(event){
            event.preventDefault();
            pengawas_pertama = $('#id_pilih_pengawas').val();
            shift_pertama = $('#id_data_shift').val();
            date_pertama = $('#id_data_date').val();
            $.ajax({
                url:"{{ route('setting_target.getData.editTarget') }}",
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
                        $('.clickReset').val('');
                        openSetTarget();
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
    });
    function main() {
        var date = $('#date_id_title').val();
        var shift = $('#shift_id_shift').val();
        var nama = $('#nama_id_nama').val();
        $.ajax({
            url:"{{ route('setting_target.search.target') }}",
            data:{date:date,shift:shift,nama:nama},
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#tbody_target').html(data.tbody_target);
                $('#date_title').html(data.data_arr['date']);
                $('#shift_title').html(data.data_arr['shift']);
                $('#nama_title').html(data.data_arr['nama']);
            },
            complete:function(){
                // Hide image container
                $("#nav-loading").hide();
            }
        });
    }
    function editTarget(nik,nama,jam){
        $('#nik-target').val(nik);
        $('#nama-target').val(nama);
        var date = $('#date_id_title').val();
        var shift = $('#shift_id_shift').val();
        $('#date-target').val(date);
        $('#shift-target').val(shift);
        $('#jam-target').val(jam);
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
