@extends('layouts.index')

@section('content')
<br>
<div class="container-fluid">
    <div class="row justify-content-center">
        <section class="col-lg-12 connectedSortable">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="text-center">
                        <p class="h4 mb-3 fw-bold mt-3">DAILY BALANCE STOCKFIT LINE</p>
                        <footer class="blockquote-footer">Buymonth : {{ date('m') }}-{{ date('m',strtotime('+2 month')) }}'{{ date('y') }}</footer>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('output_stf/balance/print_balance') }}" enctype="multipart/form-data" target="_blank">
                        @csrf
                        <button type="button" id="resetSearch" class="btn btn-info m-2">Reset Search</button>
                        <button type="submit" id="exportExcel" class="btn btn-warning m-2">Export Excel</button>
                        <div style="overflow-x:auto;" class="tableFixHead">
                            <table class="table table-striped table-hover text-center" id="resultSearch">
                                <thead>
                                    <tr>
                                        <th rowspan="2">BM</th>
                                        <th rowspan="2">CELL</th>
                                        <th rowspan="2">STYLE</th>
                                        <th rowspan="2">ARTICLE</th>
                                        <th rowspan="2">WIDE</th>
                                        <th rowspan="2">G</th>
                                        <th rowspan="2">PO</th>
                                        <th rowspan="2">XFD</th>
                                        <th rowspan="2">QTY</th>
                                        <th colspan="3">Line Stockfit</th>
                                        <th rowspan="2">Balance Input</th>
                                        <th rowspan="2">Balance Output</th>
                                    </tr>
                                    <tr>
                                        <th>Input</th>
                                        <th>Output</th>
                                        <th>WIP</th>
                                    </tr>
                                    <tr>
                                        <form method="POST" action="#" id="form_search" enctype="multipart/form-data">
                                            @csrf
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" name="bm" list="list_bm" id="bm_id_search">
                                                <datalist id="list_bm"></datalist>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" list="list_cell" name="cell" id="cell_id_search">
                                                <datalist id="list_cell"></datalist>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" list="list_style" name="style" id="style_id_search">
                                                <datalist id="list_style"></datalist>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" list="list_article" name="article" id="article_id_search">
                                                <datalist id="list_article"></datalist>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" list="list_wide" name="wide" id="wide_id_search">
                                                <datalist id="list_wide"></datalist>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" list="list_g" name="g" id="g_id_search">
                                                <datalist id="list_g"></datalist>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" list="list_po" name="po" id="po_id_search">
                                                <datalist id="list_po"></datalist>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control input-sm text-center fw-bold search_class" autocomplete="off" list="list_xfd" name="xfd" id="xfd_id_search">
                                                <datalist id="list_xfd"></datalist>
                                            </th>
                                            <th>==</th>
                                            <th>==</th>
                                            <th>==</th>
                                            <th>==</th>
                                            <th>==</th>
                                            <th>==</th>
                                        </form>
                                    </tr>
                                </thead>
                                <tbody id="main_table"></tbody>
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
<!-- Modal -->
@include('stockfit.stockfit_line.script.script_balance')
@endsection

