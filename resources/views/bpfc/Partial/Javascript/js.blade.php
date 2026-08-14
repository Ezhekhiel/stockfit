<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('dist/js/jquery-1.11.1.min.js') }}"></script> 
<script type="text/javascript" src="{{ asset('dist/js/toastr.min.js') }}"></script>
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('dist/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('dist/js/jquery-ui.js') }}"></script>
<script src="{{ asset('dist/js/popper.min.js') }}" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="{{ asset('dist/js/bootstrap.min.js') }}" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- core -->
<script src="{{ asset("plugins/jquery/core.js") }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    $(document).ready(function(){
        $('#searchData').on('keyup', function() {
            $value = $(this).val();
            $.ajax({
                url:"{{ route('show') }}",
                data:{'search':$value},
                method:"GET",
                dataType:'JSON',
                success:function(data)
                {
                    $('#id_place_image').html(data.img);
                    $('#showImage').modal('show');
                }
            });
            $('#searchData').val('');
        }); 
        
    });
    function zoomPicture(){
        alert('sukses');
    }
  </script>