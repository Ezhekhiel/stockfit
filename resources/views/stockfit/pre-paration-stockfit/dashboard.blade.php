@extends('layouts.index')

@section('content')
<br>
<div class="container-fluid">
    <div class="row justify-content-center">
        <section class="col-lg-12 connectedSortable">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="text-center">
                        <H1 class="mb-3 fw-bold" style="font-size: 200%">PACEMAKER</H1>
                        <footer class="blockquote-footer">STOCKFIT SETTING LINE</footer>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#nav-dashboard" role="tab" aria-controls="dashboard" aria-selected="true">Dashboard</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="data" aria-selected="false">Data</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="card tab-pane fade show active" id="nav-dashboard" role="tabpanel" aria-labelledby="nav-dashboard-tab">
                    <div class="right p-2">
                        <a class="close" data-toggle="collapse" href="#collapsSearch" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i id="showCollaps" aria-hidden="true">Show</i>
                        </a>
                        <div class="collapse" id="collapsSearch">
                            <div class="form-group ml-4">
                                <label for="exampleInputEmail1">Select Shift</label>
                                <select name="shift" id="id_shift_input" class="form-control form-control-sm">
                                    <option>Pilih Shift</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="NON SHIFT">NON SHIFT</option>
                                </select>
                            </div>
                            <div class="form-group ml-4">
                                <label for="exampleInputEmail1">Select Date</label>
                                <input type="date" name="date" id="id_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="card-body text-center">
                            <div style="overflow-x:auto;">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th style="font-size:120%" width="5%" class="align-middle" rowspan="2">JAM</th>
                                            <th style="font-size:130%" width="90%" id="header_pengawas">PENGAWAS</th>
                                        </tr>
                                        <tr id="table_head_pengawas_id">
                                            <th style="font-size:120%">NOT FOUND</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_dashboard">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-md-center p-4 mb-4">
                                <div class="col col-md-2 text-center">
                                    <label class="mt-2" style="font-size: 100%">TRANSFER</label>
                                    <div class="progress" style="height: 30%;">
                                        <div class="progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="col col-md-2 text-center">
                                    <label class="mt-2" style="font-size: 95%">TRANSFER TIDAK SESUAI TARGET</label>
                                    <div class="progress" style="height: 30%;">
                                        <div class="progress-bar-striped progress-bar-animated bg-dark" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="col col-md-2 text-center">
                                    <label class="mt-2" style="font-size: 100%">READY</label>
                                    <div class="progress" style="height: 30%;">
                                        <div class="progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                    </div>
                                    <label class="mt-2" style="font-size: 100%">SPK 100%</label>
                                </div>
                                <div class="col col-md-2 text-center">
                                    <label class="mt-2" style="font-size: 100%">ON GOING</label>
                                    <div class="progress" style="height: 30%;">
                                        <div class="progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                    </div>
                                    <label class="mt-2" style="font-size: 100%">50% >= SPK < 100%</label>
                                </div>
                                <div class="col col-md-2 text-center">
                                    <label class="mt-2" style="font-size: 100%">NOT READY</label>
                                    <div class="progress" style="height: 30%;">
                                        <div class="progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                    </div>
                                    <label class="mt-2" style="font-size: 100%">SPK < 50%</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                    <form method="POST" action="{{ url('/setting_line/data/export') }}" target="_blank" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        <div class="col-3 p-3">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" class="form-control form-control-sm" id="date_form_data" aria-describedby="dateHelp" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-9 text-end p-4">
                            <button class="btn btn-primary" type="submit">EXPORT</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Shift</th>
                                    <th class="text-center">Pengawas</th>
                                    <th class="text-center">Line</th>
                                    <th class="text-center">Jam</th>
                                    <th class="text-center">PO</th>
                                    <th class="text-center">Wid</th>
                                    <th class="text-center">Qty Order</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>
                                        <input type="text" list="shift_list" name="shift" class="form-control form-control-sm search" id="search_shift">
                                        <datalist id="shift_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="pengawas_list" name="pengawas" class="form-control form-control-sm search" id="search_pengawas">
                                        <datalist id="pengawas_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="line_list" name="line" class="form-control form-control-sm search" id="search_line">
                                        <datalist id="line_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="jam_list" name="jam" class="form-control form-control-sm search" id="search_jam">
                                        <datalist id="jam_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="po_list" name="po" class="form-control form-control-sm search" id="search_po">
                                        <datalist id="po_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="wide_list" name="wide" class="form-control form-control-sm search" id="search_wide">
                                        <datalist id="wide_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="qty_order_list" name="qty_order" class="form-control form-control-sm search" id="search_qty_order">
                                        <datalist id="qty_order_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="size_name_list" name="size_name" class="form-control form-control-sm search" id="search_size_name">
                                        <datalist id="size_name_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="qty_list" name="qty" class="form-control form-control-sm search" id="search_qty">
                                        <datalist id="qty_list"></datalist>
                                    </th>
                                    <th>
                                        <input type="text" list="status_list" name="status" class="form-control form-control-sm" id="search_status">
                                        <datalist id="status_list"></datalist>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbodyData"></tbody>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<div id="pageMessages"></div>
<div id="loading-spinner" style="display:none">
    <div class="loader"></div>
</div>
<div class="modal" id="modalInfo" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_dc"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped text-center">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Line</th>
                                <th>JAM</th>
                                <th>PO</th>
                                <th>WIDE</th>
                                <th>STYLE</th>
                                <th>XFD</th>
                                <th>QTY ORDER</th>
                                <th>SIZE</th>
                                <th>QTY</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_list_spk"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@include('stockfit.pre-paration-stockfit.script.dashboard')
@endsection

