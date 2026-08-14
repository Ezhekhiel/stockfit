<!DOCTYPE html>
<html>
<head>
    @include('bpfc.Partial.Head.head')
    @include('bpfc.Partial.CSS.style')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
            @yield ('contentheader')
        <!-- /.content-header -->

        <!-- Main content -->
            @yield ('content')

            @yield ('second_content')
        <!-- /.content -->
    </div>


    <!-- Footer-->

  <!-- Control Sidebar -->
  {{-- <aside class="control-sidebar control-sidebar-dark"> --}}
    <!-- Control sidebar content goes here -->
  {{-- </aside> --}}
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@include('bpfc.Partial.Javascript.js')
</body>
</html>
