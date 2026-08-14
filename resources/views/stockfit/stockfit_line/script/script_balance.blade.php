<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('dist/js/Chart.js') }}"></script>
<script>
    $(document).ready(function(){
        main();
    });
    function main() {
        $.ajax({
            url:"{{ route('output_stf.balance.main') }}",
            method:"get",
            dataType:'JSON',
            beforeSend: function(){
                $("#nav-loading").show();
            },
            success:function(data)
            {
                $('#main_table').html(data.table);
                $('#list_bm').html(data.list_bm);
                $('#list_cell').html(data.list_cell);
                $('#list_style').html(data.list_style);
                $('#list_article').html(data.list_article);
                $('#list_wide').html(data.list_wide);
                $('#list_g').html(data.list_g);
                $('#list_po').html(data.list_po);
                $('#list_xfd').html(data.list_xfd);
            },
            complete:function(data){
                $("#nav-loading").hide();
            }

        });
    }
    $('#resetSearch').on('click',function(event){
        $('.search_class').val("");
        main();
    });
    $('.search_class').on('change',function(event){
            event.preventDefault();
            var bm = $('#bm_id_search').val();
            var cell = $('#cell_id_search').val();
            var style = $('#style_id_search').val();
            var article = $('#article_id_search').val();
            var wide = $('#wide_id_search').val();
            var g = $('#g_id_search').val();
            var po = $('#po_id_search').val();
            var xfd = $('#xfd_id_search').val();
            if (bm==""&&cell==""&&style==""&&article==""&&wide==""&&g==""&&po==""&&xfd=="") {
                var bm = $('#bm_now').html();
            }
            $.ajax({
                type: "get",
                data:{bm:bm,cell:cell,style:style,article:article,wide:wide,g:g,po:po,xfd:xfd},
                url:"{{ route('output_stf.balance.search') }}",
                dataType: "JSON",
                beforeSend: function(){
                    $("#loading-spinner").show();
                },
                success:function(data)
                {
                    $('#main_table').html(data.table);
                    $('#list_bm').html(data.list_bm);
                    $('#list_cell').html(data.list_cell);
                    $('#list_style').html(data.list_style);
                    $('#list_article').html(data.list_article);
                    $('#list_wide').html(data.list_wide);
                    $('#list_g').html(data.list_g);
                    $('#list_po').html(data.list_po);
                    $('#list_xfd').html(data.list_xfd);
                },
                complete:function(){
                    $("#loading-spinner").hide();
                }
            })
        });
</script>
