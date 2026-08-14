<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('dist/js/Chart.js') }}"></script>
<script>
    $(document).ready(function(){
        main();
        $('#register-pengawas').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('register_pengawas.save_pengawas') }}",
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
                        $('.validation').val('');
                        main();
                    }
                    createAlert('','Sukses!',data.text,data.color,true,true,'pageMessages');
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
        $('#update-pengawas').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('register_pengawas.update_pengawas') }}",
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
                        $('.validation').val('');
                        $('#modal-update-pengawas').modal('hide');
                        main();
                    }
                    createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
    });
    function main() {
        $.ajax({
            url:"{{ route('register_pengawas.data_pengawas') }}",
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
    function dataPengawasUpdate(id){
        $.ajax({
            url:"{{ route('register_pengawas.getData.Pengawas') }}",
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
            url:"{{ route('register_pengawas.delete.Pengawas') }}",
            method:"get",
            data:{id:id},
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                main();
            },
            complete:function(){
                // Hide image container
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
