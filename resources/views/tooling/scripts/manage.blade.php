<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $(document).ready(function(){
        main();
        $('#form_manage_model').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('tooling.manage.save_model') }}",
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
                    if(data.alert=="sukses!");
                    {
                        main();
                        $('.resetModel').val('');
                    }
                    createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
        $('#form_manage_versi').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('tooling.manage.save_versi') }}",
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
                    if(data.alert=="sukses!");
                    {
                        main();
                        $('.resetVersi').val('');
                    }
                    createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
        $('#form_manage_remark').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('tooling.manage.save_remark') }}",
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
                    if(data.alert=="sukses!");
                    {
                        main();
                        $('.resetRemark').val('');
                    }
                    createAlert('',data.alert,data.text,data.color,true,true,'pageMessages');
                },
                complete:function(){
                    $("#nav-loading").hide();
                }
            })
        });
        $('#cancleButtonModel').on('click',function (event) {
            hideButtonUpdateModel();
            $('.resetModel').val('');
        });
        $('#cancleButtonVersi').on('click',function (event) {
            hideButtonUpdateVersi();
            $('.resetVersi').val('');
        });
        $('#cancleButtonRemark').on('click',function (event) {
            hideButtonUpdateRemark();
            $('.resetRemark').val('');
        })
    });
    //model
        function updateModel(id,model) {
            $('#inpt_manage_id_model').val(id);
            $('#inpt_manage_model').val(model);
            showButtonUpdateModel();
        }
        function showButtonUpdateModel() {
            $('#updateButtonModel').show();
            $('#cancleButtonModel').show();
            $('#insertButtonModel').hide();
        }
        function hideButtonUpdateModel(params) {
            $('#updateButtonModel').hide();
            $('#cancleButtonModel').hide();
            $('#insertButtonModel').show();
        }
    //versi
        function updateVersi(id,versi) {
            $('#inpt_manage_id_versi').val(id);
            $('#inpt_manage_versi').val(versi);
            showButtonUpdateVersi();
        }
        function showButtonUpdateVersi() {
            $('#updateButtonVersi').show();
            $('#cancleButtonVersi').show();
            $('#insertButtonVersi').hide();
        }
        function hideButtonUpdateVersi(params) {
            $('#updateButtonVersi').hide();
            $('#cancleButtonVersi').hide();
            $('#insertButtonVersi').show();
        }
    //remark
        function updateRemark(id,remark) {
            $('#inpt_manage_id_remark').val(id);
            $('#inpt_manage_remark').val(remark);
            showButtonUpdateRemark();
        }
        function showButtonUpdateRemark() {
            $('#updateButtonRemark').show();
            $('#cancleButtonRemark').show();
            $('#insertButtonRemark').hide();
        }
        function hideButtonUpdateRemark(params) {
            $('#updateButtonRemark').hide();
            $('#cancleButtonRemark').hide();
            $('#insertButtonRemark').show();
        }
    function main() {
        $.ajax({
            url:"{{ route('tooling.manage.main') }}",
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#tbody_model').html(data.table_model);
                $('#tbody_versi').html(data.table_versi);
                $('#tbody_remark').html(data.table_remark);
            },
            complete:function(data){
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
