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
                @auth
                    @if (auth()->user()->role_id==7 || auth()->user()->role_id==1 || auth()->user()->role_id==5 || auth()->user()->role_id==8)
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="input-tab" data-toggle="tab" href="#input" role="tab" aria-controls="input" aria-selected="false">INPUT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="output-tab" data-toggle="tab" href="#output" role="tab" aria-controls="output" aria-selected="false">OUTPUT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="reject-tab" data-toggle="tab" href="#reject" role="tab" aria-controls="reject" aria-selected="false">REJECT</a>
                                </li>
                                <li class="nav-item mr-auto">
                                    <a class="nav-link" onclick="showData()" href="#">DATA</a>
                                </li>
                                <li class="nav-item mr-4">
                                    <a class="nav-link" id="export-tab" data-toggle="tab" href="#export" role="tab" aria-controls="export" aria-selected="false">Export</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                @endauth
                <div class="card-body">
                    <div class="col-12 justify-content-center">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="input" role="tabpanel" aria-labelledby="input-tab">
                                <div class="col-12 card">
                                    <div class="card-header">
                                        <h5 class="fw-bold">Input Setting</h5>
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="input-sr-tab" data-toggle="tab" href="#by_size_run_input" role="tab" aria-controls="tablet" aria-selected="false">By Size Run</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="input-shortage-tab" data-toggle="tab" href="#shortage_input" role="tab" aria-controls="tablet" aria-selected="false">Shortage</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content" id="TabContentInput">
                                        <div class="tab-pane fade" id="by_size_run_input" role="tabpanel" aria-labelledby="input-input-tab">
                                            <div class="card-header">
                                                <h6 class="fw-bold">Input Stockfit</h6>
                                            </div>
                                            <div class="card-body" style="font-size: 80%">
                                                <div class="" id="alert-input-tablet" style="display:none">
                                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                                    <p id="text-alert-input-tablets"></p>
                                                </div>
                                                <form method="POST" action="#" id="form_input" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row justify-content-center">
                                                        <div class="col-3 border p-3 m-2">
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="date_id" class="form-label">Date</label>
                                                                    <input type="date" class="form-control form-control-sm" name="date" id="id_date_input" aria-describedby="dateID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_shift_input" class="form-label">Shift</label>
                                                                    <select name="shift" id="id_shift_input" class="form-control form-control-sm">
                                                                        <option>Pilih Shift</option>
                                                                        <option value="A">A</option>
                                                                        <option value="B">B</option>
                                                                        <option value="NON SHIFT">NON SHIFT</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_pengawas_input" class="form-label">Pengawas</label>
                                                                    <select name="pengawas" id="id_pengawas_input" class="form-control form-control-sm select-pengawas"></select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_line_input" class="form-label">Line</label>
                                                                    <select name="line" id="id_line_input" class="form-control form-control-sm">
                                                                        <option value="Line-1">Line-1</option>
                                                                        <option value="Line-2">Line-2</option>
                                                                        <option value="Line-3">Line-3</option>
                                                                        <option value="Line-4">Line-4</option>
                                                                        <option value="Line-5">Line-5</option>
                                                                        <option value="Line-6">Line-6</option>
                                                                        <option value="Line-7">Line-7</option>
                                                                        <option value="Line-8">Line-8</option>
                                                                        <option value="Line-9">Line-9</option>
                                                                        <option value="Line-10">Line-10</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_po_input" class="form-label">PO</label>
                                                                    <input type="text" class="form-control form-control-sm clickReset" name="po" id="id_po_input" list="po_list" aria-describedby="poID" autocomplete="off">
                                                                    <datalist id="po_list"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_jam_input" class="form-label">Jam Ke -</label>
                                                                    <select name="jam" id="id_jam_input" class="form-control form-control-sm">
                                                                        <option value="">Pilih Jam Kerja</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="wide_id" class="form-label">Wide</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="wide" id="id_wide_input" aria-describedby="wideID" list="wide_list_input" autocomplete="off">
                                                                    <datalist id="wide_list_input"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="cell_id" class="form-label">Cell</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="cell" id="id_cell_input" aria-describedby="cellID" list="cell_list_input" autocomplete="off">
                                                                    <datalist id="cell_list_input"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="article_id" class="form-label">Article / Style</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="style" id="id_style_input" aria-describedby="styleID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="qty_po_id" class="form-label">QTY PO</label>
                                                                    <input type="number" readonly class="form-control form-control-sm detail_po" name="qty_po" id="id_qty_po_input" aria-describedby="qtyID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="gender_id" class="form-label">Gender</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="gender"id="id_gender_input" aria-describedby="genderID">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 border p-3 m-2">
                                                            <div class="row border m-2">
                                                                <div class="col-12 text-center pt-4 pb-2">
                                                                    <h5 class="fw-bold">SIZE RUN</h5>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_1" id="label_size_input_1" class="col-form-label validation">1</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_1" name="qty_input[]" aria-describedby="size_input_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_2" id="label_size_input_2" class="col-form-label validation">2</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_2" name="qty_input[]" aria-describedby="size_input_2">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_3" id="label_size_input_3" class="col-form-label validation">3</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_3" name="qty_input[]" aria-describedby="size_input_3">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_4" id="label_size_input_4" class="col-form-label validation">4</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_4" name="qty_input[]" aria-describedby="size_input_4">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_5" id="label_size_input_5" class="col-form-label validation">5</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_5" name="qty_input[]" aria-describedby="size_input_5">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_6" id="label_size_input_6" class="col-form-label validation">6</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_6" name="qty_input[]" aria-describedby="size_input_6">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_7" id="label_size_input_7" class="col-form-label validation">7</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_7" name="qty_input[]" aria-describedby="size_input_7">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_8" id="label_size_input_8" class="col-form-label validation">8</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_8" name="qty_input[]" aria-describedby="size_input_8">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_9" id="label_size_input_9" class="col-form-label validation">9</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_9" name="qty_input[]" aria-describedby="size_input_9">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_10" id="label_size_input_10" class="col-form-label validation">10</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_10" name="qty_input[]" aria-describedby="size_input_10">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_11" id="label_size_input_11" class="col-form-label validation">11</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_11" name="qty_input[]" aria-describedby="size_input_11">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_12" id="label_size_input_12" class="col-form-label validation">12</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_12" name="qty_input[]" aria-describedby="size_input_12">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_13" id="label_size_input_13" class="col-form-label validation">13</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_13" name="qty_input[]" aria-describedby="size_input_13">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_14" id="label_size_input_14" class="col-form-label validation">14</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_14" name="qty_input[]" aria-describedby="size_input_14">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_15" id="label_size_input_15" class="col-form-label validation">15</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_15" name="qty_input[]" aria-describedby="size_input_15">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_16" id="label_size_input_16" class="col-form-label validation">16</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_16" name="qty_input[]" aria-describedby="size_input_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_17" id="label_size_input_17" class="col-form-label validation">17</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_17" name="qty_input[]" aria-describedby="size_input_16">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_18" id="label_size_input_18" class="col-form-label validation">18</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_18" name="qty_input[]" aria-describedby="size_input_17">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_19" id="label_size_input_19" class="col-form-label validation">19</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_19" name="qty_input[]" aria-describedby="size_input_18">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_20" id="label_size_input_20" class="col-form-label validation">20</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_20" name="qty_input[]" aria-describedby="size_input_19">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_21" id="label_size_input_21" class="col-form-label validation">21</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_21" name="qty_input[]" aria-describedby="size_input_20">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_22" id="label_size_input_22" class="col-form-label validation">22</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_22" name="qty_input[]" aria-describedby="size_input_21">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_23" id="label_size_input_23" class="col-form-label validation">23</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_23" name="qty_input[]" aria-describedby="size_input_22">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_24" id="label_size_input_24" class="col-form-label validation">24</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_24" name="qty_input[]" aria-describedby="size_input_23">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_25" id="label_size_input_25" class="col-form-label validation">25</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_25" name="qty_input[]" aria-describedby="size_input_24">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_26" id="label_size_input_26" class="col-form-label validation">26</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_26" name="qty_input[]" aria-describedby="size_input_26">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_27" id="label_size_input_27" class="col-form-label validation">27</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_27" name="qty_input[]" aria-describedby="size_input_27">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_28" id="label_size_input_28" class="col-form-label validation">28</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_28" name="qty_input[]" aria-describedby="size_input_28">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_input_29" id="label_size_input_29" class="col-form-label validation">29</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_input_29" name="qty_input[]" aria-describedby="size_input_29">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="border p-3 m-2">
                                                                <button type="submit" class="btn btn-secondary btn-lg btn-block">SAVE</button>
                                                            </div>
                                                            <div class="row border m-2">
                                                                <div class="col-12 text-center pt-4 pb-2">
                                                                    <h5 class="fw-bold">BALANCE</h5>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_1" id="label_balance_input_1" class="col-form-label validation">1</label>
                                                                        <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(1)" id="visual_input_1"  aria-describedby="visual_input_1" readonly>
                                                                        <input type="hidden" id="balance_input_1" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_2" id="label_balance_input_2" class="col-form-label">2</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(2)" id="visual_input_2" aria-describedby="visual_input_2" readonly>
                                                                            <input type="hidden" id="balance_input_2" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_3" id="label_balance_input_3" class="col-form-label">3</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(3)" id="visual_input_3" aria-describedby="visual_input_3" readonly>
                                                                            <input type="hidden" id="balance_input_3" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_4" id="label_balance_input_4" class="col-form-label">4</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(4)" id="visual_input_4" aria-describedby="visual_input_4" readonly>
                                                                            <input type="hidden" id="balance_input_4" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_5" id="label_balance_input_5" class="col-form-label">5</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(5)" id="visual_input_5" aria-describedby="visual_input_5" readonly>
                                                                            <input type="hidden" id="balance_input_5" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_6" id="label_balance_input_6" class="col-form-label">6</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(6)" id="visual_input_6" aria-describedby="visual_input_6" readonly>
                                                                            <input type="hidden" id="balance_input_6" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_7" id="label_balance_input_7" class="col-form-label">7</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(7)" id="visual_input_7" aria-describedby="visual_input_7" readonly>
                                                                            <input type="hidden" id="balance_input_7" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_8" id="label_balance_input_8" class="col-form-label">8</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(8)" id="visual_input_8" aria-describedby="visual_input_8" readonly>
                                                                            <input type="hidden" id="balance_input_8" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_9" id="label_balance_input_9" class="col-form-label">9</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(9)" id="visual_input_9" aria-describedby="visual_input_9" readonly>
                                                                            <input type="hidden" id="balance_input_9" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_10" id="label_balance_input_10" class="col-form-label">10</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(10)" id="visual_input_10" aria-describedby="visual_input_10" readonly>
                                                                            <input type="hidden" id="balance_input_10" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_11" id="label_balance_input_11" class="col-form-label">11</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(11)" id="visual_input_11" aria-describedby="visual_input_11" readonly>
                                                                            <input type="hidden" id="balance_input_11" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_12" id="label_balance_input_12" class="col-form-label">12</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(12)" id="visual_input_12" aria-describedby="visual_input_12" readonly>
                                                                            <input type="hidden" id="balance_input_12" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_13" id="label_balance_input_13" class="col-form-label">13</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(13)" id="visual_input_13" aria-describedby="visual_input_13" readonly>
                                                                            <input type="hidden" id="balance_input_13" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_14" id="label_balance_input_14" class="col-form-label">14</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(14)" id="visual_input_14" aria-describedby="visual_input_14" readonly>
                                                                            <input type="hidden" id="balance_input_14" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_15" id="label_balance_input_15" class="col-form-label">15</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(15)" id="visual_input_15" aria-describedby="visual_input_15" readonly>
                                                                            <input type="hidden" id="balance_input_15" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_16" id="label_balance_input_16" class="col-form-label">16</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(16)" id="visual_input_16" aria-describedby="visual_input_16" readonly>
                                                                            <input type="hidden" id="balance_input_16" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_17" id="label_balance_input_17" class="col-form-label">17</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(17)" id="visual_input_17" aria-describedby="visual_input_17" readonly>
                                                                            <input type="hidden" id="balance_input_17" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_18" id="label_balance_input_18" class="col-form-label">18</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(18)" id="visual_input_18" aria-describedby="visual_input_18" readonly>
                                                                            <input type="hidden" id="balance_input_18" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_19" id="label_balance_input_19" class="col-form-label">19</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(19)" id="visual_input_19" aria-describedby="visual_input_19" readonly>
                                                                            <input type="hidden" id="balance_input_19" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_20" id="label_balance_input_20" class="col-form-label">20</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(20)" id="visual_input_20" aria-describedby="visual_input_20" readonly>
                                                                            <input type="hidden" id="balance_input_20" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_21" id="label_balance_input_21" class="col-form-label">21</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(21)" id="visual_input_21" aria-describedby="visual_input_21" readonly>
                                                                            <input type="hidden" id="balance_input_21" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_22" id="label_balance_input_22" class="col-form-label">22</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(22)" id="visual_input_22" aria-describedby="visual_input_22" readonly>
                                                                            <input type="hidden" id="balance_input_22" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_23" id="label_balance_input_23" class="col-form-label">23</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(23)" id="visual_input_23" aria-describedby="visual_input_23" readonly>
                                                                            <input type="hidden" id="balance_input_23" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_24" id="label_balance_input_24" class="col-form-label">24</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(24)" id="visual_input_24"aria-describedby="visual_input_24" readonly>
                                                                            <input type="hidden" id="balance_input_24" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_25" id="label_balance_input_25" class="col-form-label">25</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(25)" id="visual_input_25" aria-describedby="visual_input_25" readonly>
                                                                            <input type="hidden" id="balance_input_25" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_26" id="label_balance_input_26" class="col-form-label">26</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(26)" id="visual_input_26" aria-describedby="visual_input_26" readonly>
                                                                            <input type="hidden" id="balance_input_26" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_27" id="label_balance_input_27" class="col-form-label">27</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(27)" id="visual_input_27" aria-describedby="visual_input_27" readonly>
                                                                            <input type="hidden" id="balance_input_27" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_28" id="label_balance_input_28" class="col-form-label">28</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(28)" id="visual_input_28" aria-describedby="visual_input_28" readonly>
                                                                            <input type="hidden" id="balance_input_28" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_input_29" id="label_balance_input_29" class="col-form-label">29</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(29)" id="visual_input_29" aria-describedby="visual_input_29" readonly>
                                                                            <input type="hidden" id="balance_input_29" class="balance_input_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="shortage_input" role="tabpanel" aria-labelledby="input-input-tab">
                                            <div class="card-header">
                                                <h6 class="fw-bold">Input Shortage</h6>
                                            </div>
                                            <div class="card-body" style="font-size:80%">
                                                <form method="POST" action="#" id="form_shortage_input" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row justify-content-center">
                                                        <div class="col-3 border p-3 m-2">
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="date_id" class="form-label">Date</label>
                                                                    <input type="date" class="form-control form-control-sm" name="date" id="id_date_shortage_input" aria-describedby="dateID" value="{{ date("Y-m-d") }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_shift_shortage_input" class="form-label">Shift</label>
                                                                    <select name="shift" id="id_shift_shortage_input" class="form-control form-control-sm">
                                                                        <option>Pilih Shift</option>
                                                                        <option value="A">A</option>
                                                                        <option value="B">B</option>
                                                                        <option value="NON SHIFT">NON SHIFT</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_pengawas_input" class="form-label">Pengawas</label>
                                                                    <select name="pengawas" id="id_pengawas_shortage_input" class="form-control form-control-sm select-pengawas"></select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_line_shortage_input" class="form-label">Line</label>
                                                                    <select name="line" id="id_line_shortage_input" class="form-control form-control-sm">
                                                                        <option value="Line-1">Line-1</option>
                                                                        <option value="Line-2">Line-2</option>
                                                                        <option value="Line-3">Line-3</option>
                                                                        <option value="Line-4">Line-4</option>
                                                                        <option value="Line-5">Line-5</option>
                                                                        <option value="Line-6">Line-6</option>
                                                                        <option value="Line-7">Line-7</option>
                                                                        <option value="Line-8">Line-8</option>
                                                                        <option value="Line-9">Line-9</option>
                                                                        <option value="Line-10">Line-10</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_po_shortage_input" class="form-label">PO</label>
                                                                    <input type="text" class="form-control form-control-sm clickReset" name="po" id="id_po_shortage_input" list="po_list" aria-describedby="poID" autocomplete="off">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_jam_shortage_input" class="form-label">Jam Ke -</label>
                                                                    <select name="jam" id="id_jam_shortage_input" class="form-control form-control-sm">
                                                                        <option value="">Pilih Jam Kerja</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="wide_id" class="form-label">Wide</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="wide" id="id_wide_shortage_input" aria-describedby="wideID" list="wide_list_input" autocomplete="off">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="cell_id" class="form-label">Cell</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="cell" id="id_cell_shortage_input" aria-describedby="cellID" list="cell_list_input" autocomplete="off">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="article_id" class="form-label">Article / Style</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="style" id="id_style_shortage_input" aria-describedby="styleID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="qty_po_id" class="form-label">QTY PO</label>
                                                                    <input type="number" readonly class="form-control form-control-sm detail_po" name="qty_po" id="id_qty_po_shortage_input" aria-describedby="qtyID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="gender_id" class="form-label">Gender</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="gender"id="id_gender_shortage_input" aria-describedby="genderID">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 border p-3 m-2">
                                                            <div class="border p-3 m-2">
                                                                <button type="submit" class="btn btn-secondary btn-lg btn-block">SAVE</button>
                                                            </div>
                                                            <div class="row border m-2">
                                                                <div class="col-12 text-center pt-4 pb-2">
                                                                    <h5 class="fw-bold">SIZE RUN</h5>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_1" id="label_size_shortage_input_1" class="col-form-label validation">1</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_1" name="qty_shortage_input[]" aria-describedby="size_shortage_input_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_2" id="label_size_shortage_input_2" class="col-form-label validation">2</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_2" name="qty_shortage_input[]" aria-describedby="size_shortage_input_2">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_3" id="label_size_shortage_input_3" class="col-form-label validation">3</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_3" name="qty_shortage_input[]" aria-describedby="size_shortage_input_3">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_4" id="label_size_shortage_input_4" class="col-form-label validation">4</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_4" name="qty_shortage_input[]" aria-describedby="size_shortage_input_4">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_5" id="label_size_shortage_input_5" class="col-form-label validation">5</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_5" name="qty_shortage_input[]" aria-describedby="size_shortage_input_5">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_6" id="label_size_shortage_input_6" class="col-form-label validation">6</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_6" name="qty_shortage_input[]" aria-describedby="size_shortage_input_6">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_7" id="label_size_shortage_input_7" class="col-form-label validation">7</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_7" name="qty_shortage_input[]" aria-describedby="size_shortage_input_7">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_8" id="label_size_shortage_input_8" class="col-form-label validation">8</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_8" name="qty_shortage_input[]" aria-describedby="size_shortage_input_8">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_9" id="label_size_shortage_input_9" class="col-form-label validation">9</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_9" name="qty_shortage_input[]" aria-describedby="size_shortage_input_9">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_10" id="label_size_shortage_input_10" class="col-form-label validation">10</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_10" name="qty_shortage_input[]" aria-describedby="size_shortage_input_10">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_11" id="label_size_shortage_input_11" class="col-form-label validation">11</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_11" name="qty_shortage_input[]" aria-describedby="size_shortage_input_11">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_12" id="label_size_shortage_input_12" class="col-form-label validation">12</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_12" name="qty_shortage_input[]" aria-describedby="size_shortage_input_12">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_13" id="label_size_shortage_input_13" class="col-form-label validation">13</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_13" name="qty_shortage_input[]" aria-describedby="size_shortage_input_13">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_14" id="label_size_shortage_input_14" class="col-form-label validation">14</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_14" name="qty_shortage_input[]" aria-describedby="size_shortage_input_14">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_15" id="label_size_shortage_input_15" class="col-form-label validation">15</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_15" name="qty_shortage_input[]" aria-describedby="size_shortage_input_15">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_16" id="label_size_shortage_input_16" class="col-form-label validation">16</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_16" name="qty_shortage_input[]" aria-describedby="size_shortage_input_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_17" id="label_size_shortage_input_17" class="col-form-label validation">17</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_17" name="qty_shortage_input[]" aria-describedby="size_shortage_input_16">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_18" id="label_size_shortage_input_18" class="col-form-label validation">18</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_18" name="qty_shortage_input[]" aria-describedby="size_shortage_input_17">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_19" id="label_size_shortage_input_19" class="col-form-label validation">19</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_19" name="qty_shortage_input[]" aria-describedby="size_shortage_input_18">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_20" id="label_size_shortage_input_20" class="col-form-label validation">20</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_20" name="qty_shortage_input[]" aria-describedby="size_shortage_input_19">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_21" id="label_size_shortage_input_21" class="col-form-label validation">21</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_21" name="qty_shortage_input[]" aria-describedby="size_shortage_input_20">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_22" id="label_size_shortage_input_22" class="col-form-label validation">22</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_22" name="qty_shortage_input[]" aria-describedby="size_shortage_input_21">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_23" id="label_size_shortage_input_23" class="col-form-label validation">23</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_23" name="qty_shortage_input[]" aria-describedby="size_shortage_input_22">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_24" id="label_size_shortage_input_24" class="col-form-label validation">24</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_24" name="qty_shortage_input[]" aria-describedby="size_shortage_input_23">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_25" id="label_size_shortage_input_25" class="col-form-label validation">25</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_25" name="qty_shortage_input[]" aria-describedby="size_shortage_input_24">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_26" id="label_size_shortage_input_26" class="col-form-label validation">26</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_26" name="qty_shortage_input[]" aria-describedby="size_shortage_input_26">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_27" id="label_size_shortage_input_27" class="col-form-label validation">27</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_27" name="qty_shortage_input[]" aria-describedby="size_shortage_input_27">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_28" id="label_size_shortage_input_28" class="col-form-label validation">28</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_28" name="qty_shortage_input[]" aria-describedby="size_shortage_input_28">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_input_29" id="label_size_shortage_input_29" class="col-form-label validation">29</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_input_29" name="qty_shortage_input[]" aria-describedby="size_shortage_input_29">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="output" role="tabpanel" aria-labelledby="output-tab">
                                <div class="col-12 card">
                                    <div class="card-header">
                                        <h5 class="fw-bold">Output Stockfit</h5>
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="output-admin-tab" data-toggle="tab" href="#by_size_run_output" role="tab" aria-controls="admin" aria-selected="false">By Size Run</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="output-shortage-tab" data-toggle="tab" href="#shortage_output" role="tab" aria-controls="admin" aria-selected="false">Shortage</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content" id="TabContentOutput">
                                        <div class="tab-pane fade" id="by_size_run_output" role="tabpanel" aria-labelledby="output-admin-tab">
                                            <div class="card-header">
                                                <h6 class="fw-bold">Output Stockfit</h6>
                                            </div>
                                            <div class="card-body">
                                                <form method="POST" action="#" id="form_output" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row justify-content-center" style="font-size: 80%">
                                                        <div class="col-3 border p-3 m-2">
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="date_id" class="form-label">Date</label>
                                                                    <input type="date" class="form-control form-control-sm" name="date" id="id_date_output" aria-describedby="dateID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_shift_output" class="form-label">Shift</label>
                                                                    <select name="shift" id="id_shift_output" class="form-control form-control-sm">
                                                                        <option>Pilih Shift</option>
                                                                        <option value="A">A</option>
                                                                        <option value="B">B</option>
                                                                        <option value="NON SHIFT">NON SHIFT</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_pengawas_input" class="form-label">Pengawas</label>
                                                                    <select name="pengawas" id="id_pengawas_output" class="form-control form-control-sm select-pengawas"></select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_line_output" class="form-label">Line</label>
                                                                    <select name="line" id="id_line_output" class="form-control form-control-sm">
                                                                        <option value="Line-1">Line-1</option>
                                                                        <option value="Line-2">Line-2</option>
                                                                        <option value="Line-3">Line-3</option>
                                                                        <option value="Line-4">Line-4</option>
                                                                        <option value="Line-5">Line-5</option>
                                                                        <option value="Line-6">Line-6</option>
                                                                        <option value="Line-7">Line-7</option>
                                                                        <option value="Line-8">Line-8</option>
                                                                        <option value="Line-9">Line-9</option>
                                                                        <option value="Line-10">Line-10</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_po_output" class="form-label">PO</label>
                                                                    <input type="text" class="form-control form-control-sm clickReset" name="po" id="id_po_output" list="output_po_list" aria-describedby="poID" autocomplete="off">
                                                                    <datalist id="output_po_list"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_jam_output" class="form-label">Jam Ke -</label>
                                                                    <select name="jam" id="id_jam_output" class="form-control form-control-sm">
                                                                        <option value="">Pilih Jam Kerja</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="wide_id" class="form-label">Wide</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="wide" id="id_wide_output" aria-describedby="wideID" list="wide_list_output" autocomplete="off">
                                                                    <datalist id="wide_list_output"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="cell_id" class="form-label">Cell</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="cell" id="id_cell_output" aria-describedby="cellID" list="cell_list_output" autocomplete="off">
                                                                    <datalist id="cell_list_output"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="article_id" class="form-label">Article / Style</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="style" id="id_style_output" aria-describedby="styleID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="qty_po_id" class="form-label">QTY PO</label>
                                                                    <input type="number" readonly class="form-control form-control-sm detail_po" name="qty_po" id="id_qty_po_output" aria-describedby="qtyID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="gender_id" class="form-label">Gender</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="gender"id="id_gender_output" aria-describedby="genderID">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 border p-3 m-2">
                                                            <div class="row border m-2">
                                                                <div class="col-12 text-center pt-4 pb-2">
                                                                    <h5 class="fw-bold">SIZE RUN</h5>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_1" id="label_size_output_1" class="col-form-label validation">1</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_1" name="qty_output[]" aria-describedby="size_output_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_2" id="label_size_output_2" class="col-form-label validation">2</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_2" name="qty_output[]" aria-describedby="size_output_2">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_3" id="label_size_output_3" class="col-form-label validation">3</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_3" name="qty_output[]" aria-describedby="size_output_3">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_4" id="label_size_output_4" class="col-form-label validation">4</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_4" name="qty_output[]" aria-describedby="size_output_4">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_5" id="label_size_output_5" class="col-form-label validation">5</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_5" name="qty_output[]" aria-describedby="size_output_5">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_6" id="label_size_output_6" class="col-form-label validation">6</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_6" name="qty_output[]" aria-describedby="size_output_6">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_7" id="label_size_output_7" class="col-form-label validation">7</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_7" name="qty_output[]" aria-describedby="size_output_7">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_8" id="label_size_output_8" class="col-form-label validation">8</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_8" name="qty_output[]" aria-describedby="size_output_8">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_9" id="label_size_output_9" class="col-form-label validation">9</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_9" name="qty_output[]" aria-describedby="size_output_9">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_10" id="label_size_output_10" class="col-form-label validation">10</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_10" name="qty_output[]" aria-describedby="size_output_10">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_11" id="label_size_output_11" class="col-form-label validation">11</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_11" name="qty_output[]" aria-describedby="size_output_11">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_12" id="label_size_output_12" class="col-form-label validation">12</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_12" name="qty_output[]" aria-describedby="size_output_12">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_13" id="label_size_output_13" class="col-form-label validation">13</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_13" name="qty_output[]" aria-describedby="size_output_13">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_14" id="label_size_output_14" class="col-form-label validation">14</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_14" name="qty_output[]" aria-describedby="size_output_14">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_15" id="label_size_output_15" class="col-form-label validation">15</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_15" name="qty_output[]" aria-describedby="size_output_15">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_16" id="label_size_output_16" class="col-form-label validation">16</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_16" name="qty_output[]" aria-describedby="size_output_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_17" id="label_size_output_17" class="col-form-label validation">17</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_17" name="qty_output[]" aria-describedby="size_output_16">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_18" id="label_size_output_18" class="col-form-label validation">18</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_18" name="qty_output[]" aria-describedby="size_output_17">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_19" id="label_size_output_19" class="col-form-label validation">19</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_19" name="qty_output[]" aria-describedby="size_output_18">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_20" id="label_size_output_20" class="col-form-label validation">20</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_20" name="qty_output[]" aria-describedby="size_output_19">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_21" id="label_size_output_21" class="col-form-label validation">21</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_21" name="qty_output[]" aria-describedby="size_output_20">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_22" id="label_size_output_22" class="col-form-label validation">22</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_22" name="qty_output[]" aria-describedby="size_output_21">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_23" id="label_size_output_23" class="col-form-label validation">23</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_23" name="qty_output[]" aria-describedby="size_output_22">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_24" id="label_size_output_24" class="col-form-label validation">24</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_24" name="qty_output[]" aria-describedby="size_output_23">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_25" id="label_size_output_25" class="col-form-label validation">25</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_25" name="qty_output[]" aria-describedby="size_output_24">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_26" id="label_size_output_26" class="col-form-label validation">26</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_26" name="qty_output[]" aria-describedby="size_output_26">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_27" id="label_size_output_27" class="col-form-label validation">27</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_27" name="qty_output[]" aria-describedby="size_output_27">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_28" id="label_size_output_28" class="col-form-label validation">28</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_28" name="qty_output[]" aria-describedby="size_output_28">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_output_29" id="label_size_output_29" class="col-form-label validation">29</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_output_29" name="qty_output[]" aria-describedby="size_output_29">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="border m-2 p-3">
                                                                <button type="submit" class="btn btn-secondary btn-lg btn-block">SAVE</button>
                                                            </div>
                                                            <div class="row border m-2">
                                                                <div class="col-12 text-center pt-4 pb-2">
                                                                    <h5 class="fw-bold">BALANCE</h5>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_1" id="label_balance_output_1" class="col-form-label validation">1</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(1)" id="visual_output_1"  aria-describedby="balance_output_1" readonly>
                                                                            <input type="hidden" id="balance_output_1" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_2" id="label_balance_output_2" class="col-form-label">2</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(2)" id="visual_output_2" aria-describedby="visual_output_2" readonly>
                                                                            <input type="hidden" id="balance_output_2" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_3" id="label_balance_output_3" class="col-form-label">3</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(3)" id="visual_output_3" aria-describedby="visual_output_3" readonly>
                                                                            <input type="hidden" id="balance_output_3" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_4" id="label_balance_output_4" class="col-form-label">4</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(4)" id="visual_output_4" aria-describedby="visual_output_4" readonly>
                                                                            <input type="hidden" id="balance_output_4" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_5" id="label_balance_output_5" class="col-form-label">5</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(5)" id="visual_output_5" aria-describedby="visual_output_5" readonly>
                                                                            <input type="hidden" id="balance_output_5" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_6" id="label_balance_output_6" class="col-form-label">6</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(6)" id="visual_output_6" aria-describedby="visual_output_6" readonly>
                                                                            <input type="hidden" id="balance_output_6" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_7" id="label_balance_output_7" class="col-form-label">7</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(7)" id="visual_output_7" aria-describedby="visual_output_7" readonly>
                                                                            <input type="hidden" id="balance_output_7" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_8" id="label_balance_output_8" class="col-form-label">8</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(8)" id="visual_output_8" aria-describedby="visual_output_8" readonly>
                                                                            <input type="hidden" id="balance_output_8" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_9" id="label_balance_output_9" class="col-form-label">9</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(9)" id="visual_output_9" aria-describedby="visual_output_9" readonly>
                                                                            <input type="hidden" id="balance_output_9" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_10" id="label_balance_output_10" class="col-form-label">10</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(10)" id="visual_output_10" aria-describedby="visual_output_10" readonly>
                                                                            <input type="hidden" id="balance_output_10" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_11" id="label_balance_output_11" class="col-form-label">11</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(11)" id="visual_output_11" aria-describedby="visual_output_11" readonly>
                                                                            <input type="hidden" id="balance_output_11" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_12" id="label_balance_output_12" class="col-form-label">12</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(12)" id="visual_output_12" aria-describedby="visual_output_12" readonly>
                                                                            <input type="hidden" id="balance_output_12" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_13" id="label_balance_output_13" class="col-form-label">13</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(13)" id="visual_output_13" aria-describedby="visual_output_13" readonly>
                                                                            <input type="hidden" id="balance_output_13" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_14" id="label_balance_output_14" class="col-form-label">14</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(14)" id="visual_output_14" aria-describedby="visual_output_14" readonly>
                                                                            <input type="hidden" id="balance_output_14" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_15" id="label_balance_output_15" class="col-form-label">15</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(15)" id="visual_output_15" aria-describedby="visual_output_15" readonly>
                                                                            <input type="hidden" id="balance_output_15" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_16" id="label_balance_output_16" class="col-form-label">16</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(16)" id="visual_output_16" aria-describedby="visual_output_16" readonly>
                                                                            <input type="hidden" id="balance_output_16" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_17" id="label_balance_output_17" class="col-form-label">17</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(17)" id="visual_output_17" aria-describedby="visual_output_17" readonly>
                                                                            <input type="hidden" id="balance_output_17" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_18" id="label_balance_output_18" class="col-form-label">18</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(18)" id="visual_output_18" aria-describedby="visual_output_18" readonly>
                                                                            <input type="hidden" id="balance_output_18" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_19" id="label_balance_output_19" class="col-form-label">19</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(19)" id="visual_output_19" aria-describedby="visual_output_19" readonly>
                                                                            <input type="hidden" id="balance_output_19" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_20" id="label_balance_output_20" class="col-form-label">20</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(20)" id="visual_output_20" aria-describedby="visual_output_20" readonly>
                                                                            <input type="hidden" id="balance_output_20" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_21" id="label_balance_output_21" class="col-form-label">21</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(21)" id="visual_output_21" aria-describedby="visual_output_21" readonly>
                                                                            <input type="hidden" id="balance_output_21" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_22" id="label_balance_output_22" class="col-form-label">22</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(22)" id="visual_output_22" aria-describedby="visual_output_22" readonly>
                                                                            <input type="hidden" id="balance_output_22" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_23" id="label_balance_output_23" class="col-form-label">23</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(23)" id="visual_output_23" aria-describedby="visual_output_23" readonly>
                                                                            <input type="hidden" id="balance_output_23" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_24" id="label_balance_output_24" class="col-form-label">24</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(24)" id="visual_output_24"aria-describedby="visual_output_24" readonly>
                                                                            <input type="hidden" id="balance_output_24" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_25" id="label_balance_output_25" class="col-form-label">25</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(25)" id="visual_output_25" aria-describedby="visual_output_25" readonly>
                                                                            <input type="hidden" id="balance_output_25" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_26" id="label_balance_output_26" class="col-form-label">26</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(26)" id="visual_output_26" aria-describedby="visual_output_26" readonly>
                                                                            <input type="hidden" id="balance_output_26" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_27" id="label_balance_output_27" class="col-form-label">27</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(27)" id="visual_output_27" aria-describedby="visual_output_27" readonly>
                                                                            <input type="hidden" id="balance_output_27" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_28" id="label_balance_output_28" class="col-form-label">28</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(28)" id="visual_output_28" aria-describedby="visual_output_28" readonly>
                                                                            <input type="hidden" id="balance_output_28" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="balance_output_29" id="label_balance_output_29" class="col-form-label">29</label>
                                                                            <input type="text" class="form-control form-control-sm validation_balance" onclick="openModal(29)" id="visual_output_29" aria-describedby="visual_output_29" readonly>
                                                                            <input type="hidden" id="balance_output_29" class="balance_output_value" name="qty_balance[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="shortage_output" role="tabpanel" aria-labelledby="input-input-tab">
                                            <div class="card-header">
                                                <h6 class="fw-bold">Output Shortage</h6>
                                            </div>
                                            <div class="card-body" style="font-size:80%">
                                                <form method="POST" action="#" id="form_shortage_output" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row justify-content-center">
                                                        <div class="col-3 border p-3 m-2">
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="date_id" class="form-label">Date</label>
                                                                    <input type="date" class="form-control form-control-sm" name="date" id="id_date_shortage_output" aria-describedby="dateID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_shift_shortage_output" class="form-label">Shift</label>
                                                                    <select name="shift" id="id_shift_shortage_output" class="form-control form-control-sm">
                                                                        <option>Pilih Shift</option>
                                                                        <option value="A">A</option>
                                                                        <option value="B">B</option>
                                                                        <option value="NON SHIFT">NON SHIFT</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_pengawas_input" class="form-label">Pengawas</label>
                                                                    <select name="pengawas" id="id_pengawas_shortage_output" class="form-control form-control-sm select-pengawas"></select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_line_shortage_output" class="form-label">Line</label>
                                                                    <select name="line" id="id_line_shortage_output" class="form-control form-control-sm">
                                                                        <option value="Line-1">Line-1</option>
                                                                        <option value="Line-2">Line-2</option>
                                                                        <option value="Line-3">Line-3</option>
                                                                        <option value="Line-4">Line-4</option>
                                                                        <option value="Line-5">Line-5</option>
                                                                        <option value="Line-6">Line-6</option>
                                                                        <option value="Line-7">Line-7</option>
                                                                        <option value="Line-8">Line-8</option>
                                                                        <option value="Line-9">Line-9</option>
                                                                        <option value="Line-10">Line-10</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_po_shortage_output" class="form-label">PO</label>
                                                                    <input type="text" class="form-control form-control-sm clickReset" name="po" id="id_po_shortage_output" list="output_shortage_po_list" aria-describedby="poID" autocomplete="off">
                                                                    <datalist id="output_shortage_po_list"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_jam_shortage_output" class="form-label">Jam Ke -</label>
                                                                    <select name="jam" id="id_jam_shortage_output" class="form-control form-control-sm">
                                                                        <option value="">Pilih Jam Kerja</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="card p-2 mt-4">
                                                                <div class="mb-2">
                                                                    <label for="wide_id" class="form-label">Wide</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="wide" id="id_wide_shortage_output" aria-describedby="wideID" list="wide_list_shortage_output" autocomplete="off">
                                                                    <datalist id="wide_list_shortage_output"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="cell_id" class="form-label">Cell</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="cell" id="id_cell_shortage_output" aria-describedby="cellID" list="cell_list_shortage_output" autocomplete="off">
                                                                    <datalist id="cell_list_shortage_output"></datalist>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="article_id" class="form-label">Article / Style</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="style" id="id_style_shortage_output" aria-describedby="styleID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="qty_po_id" class="form-label">QTY PO</label>
                                                                    <input type="number" readonly class="form-control form-control-sm detail_po" name="qty_po" id="id_qty_po_shortage_output" aria-describedby="qtyID">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="gender_id" class="form-label">Gender</label>
                                                                    <input type="text" readonly class="form-control form-control-sm detail_po" name="gender"id="id_gender_shortage_output" aria-describedby="genderID">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 border p-3 m-2">
                                                            <div class="border m-2 p-3">
                                                                <button type="submit" class="btn btn-secondary btn-lg btn-block">SAVE</button>
                                                            </div>
                                                            <div class="row border m-2">
                                                                <div class="col-12 text-center pt-4 pb-2">
                                                                    <h5 class="fw-bold">SIZE RUN</h5>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_1" id="label_size_shortage_output_1" class="col-form-label validation">1</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_1" name="qty_shortage_output[]" aria-describedby="size_shortage_output_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_2" id="label_size_shortage_output_2" class="col-form-label validation">2</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_2" name="qty_shortage_output[]" aria-describedby="size_shortage_output_2">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_3" id="label_size_shortage_output_3" class="col-form-label validation">3</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_3" name="qty_shortage_output[]" aria-describedby="size_shortage_output_3">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_4" id="label_size_shortage_output_4" class="col-form-label validation">4</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_4" name="qty_shortage_output[]" aria-describedby="size_shortage_output_4">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_5" id="label_size_shortage_output_5" class="col-form-label validation">5</label>
                                                                        <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_5" name="qty_shortage_output[]" aria-describedby="size_shortage_output_5">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_6" id="label_size_shortage_output_6" class="col-form-label validation">6</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_6" name="qty_shortage_output[]" aria-describedby="size_shortage_output_6">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_7" id="label_size_shortage_output_7" class="col-form-label validation">7</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_7" name="qty_shortage_output[]" aria-describedby="size_shortage_output_7">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_8" id="label_size_shortage_output_8" class="col-form-label validation">8</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_8" name="qty_shortage_output[]" aria-describedby="size_shortage_output_8">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_9" id="label_size_shortage_output_9" class="col-form-label validation">9</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_9" name="qty_shortage_output[]" aria-describedby="size_shortage_output_9">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_10" id="label_size_shortage_output_10" class="col-form-label validation">10</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_10" name="qty_shortage_output[]" aria-describedby="size_shortage_output_10">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_11" id="label_size_shortage_output_11" class="col-form-label validation">11</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_11" name="qty_shortage_output[]" aria-describedby="size_shortage_output_11">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_12" id="label_size_shortage_output_12" class="col-form-label validation">12</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_12" name="qty_shortage_output[]" aria-describedby="size_shortage_output_12">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_13" id="label_size_shortage_output_13" class="col-form-label validation">13</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_13" name="qty_shortage_output[]" aria-describedby="size_shortage_output_13">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_14" id="label_size_shortage_output_14" class="col-form-label validation">14</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_14" name="qty_shortage_output[]" aria-describedby="size_shortage_output_14">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_15" id="label_size_shortage_output_15" class="col-form-label validation">15</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_15" name="qty_shortage_output[]" aria-describedby="size_shortage_output_15">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_16" id="label_size_shortage_output_16" class="col-form-label validation">16</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_16" name="qty_shortage_output[]" aria-describedby="size_shortage_output_1">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_17" id="label_size_shortage_output_17" class="col-form-label validation">17</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_17" name="qty_shortage_output[]" aria-describedby="size_shortage_output_16">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_18" id="label_size_shortage_output_18" class="col-form-label validation">18</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_18" name="qty_shortage_output[]" aria-describedby="size_shortage_output_17">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_19" id="label_size_shortage_output_19" class="col-form-label validation">19</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_19" name="qty_shortage_output[]" aria-describedby="size_shortage_output_18">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_20" id="label_size_shortage_output_20" class="col-form-label validation">20</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_20" name="qty_shortage_output[]" aria-describedby="size_shortage_output_19">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_21" id="label_size_shortage_output_21" class="col-form-label validation">21</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_21" name="qty_shortage_output[]" aria-describedby="size_shortage_output_20">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_22" id="label_size_shortage_output_22" class="col-form-label validation">22</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_22" name="qty_shortage_output[]" aria-describedby="size_shortage_output_21">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_23" id="label_size_shortage_output_23" class="col-form-label validation">23</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_23" name="qty_shortage_output[]" aria-describedby="size_shortage_output_22">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_24" id="label_size_shortage_output_24" class="col-form-label validation">24</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_24" name="qty_shortage_output[]" aria-describedby="size_shortage_output_23">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_25" id="label_size_shortage_output_25" class="col-form-label validation">25</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_25" name="qty_shortage_output[]" aria-describedby="size_shortage_output_24">
                                                                    </div>
                                                                </div>
                                                                <div class="col-2 text-center">
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_26" id="label_size_shortage_output_26" class="col-form-label validation">26</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_26" name="qty_shortage_output[]" aria-describedby="size_shortage_output_26">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_27" id="label_size_shortage_output_27" class="col-form-label validation">27</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_27" name="qty_shortage_output[]" aria-describedby="size_shortage_output_27">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_28" id="label_size_shortage_output_28" class="col-form-label validation">28</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_28" name="qty_shortage_output[]" aria-describedby="size_shortage_output_28">
                                                                    </div>
                                                                    <div class="mb-2 form-group">
                                                                        <label for="size_shortage_output_29" id="label_size_shortage_output_29" class="col-form-label validation">29</label>
                                                                            <input type="number" class="form-control form-control-sm clickReset validation" id="size_shortage_output_29" name="qty_shortage_output[]" aria-describedby="size_shortage_output_29">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="reject" role="tabpanel" aria-labelledby="reject-tab">
                                <div class="col-12 card">
                                    <div class="card-header">
                                        <h5 class="fw-bold">Reject Stockfit</h5>
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="reject-admin-tab" data-toggle="tab" href="#by_size_run_reject" role="tab" aria-controls="reject_admin" aria-selected="false">By Size Run</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content" id="TabContentReject">
                                        <div class="tab-pane fade" id="by_size_run_reject" role="tabpanel" aria-labelledby="reject-admin-tab">
                                            <div class="card-body">
                                                <form method="POST" action="#" id="form_reject" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row justify-content-center" style="font-size: 80%">
                                                        <div class="col-12 border p-3">
                                                            <button type="submit" class="btn btn-secondary btn-lg btn-block">SAVE</button>
                                                        </div>
                                                        <div class="col-3 border p-3 m-2">
                                                            <div class="mb-2">
                                                                <label for="id_shift_reject" class="form-label">Shift</label>
                                                                <select name="shift" id="id_shift_reject" class="form-control form-control-sm">
                                                                    <option>Pilih Shift</option>
                                                                    <option value="A">A</option>
                                                                    <option value="B">B</option>
                                                                    <option value="NON SHIFT">NON SHIFT</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="id_pengawas_input" class="form-label">Pengawas</label>
                                                                <select name="pengawas" id="id_pengawas_output" class="form-control form-control-sm select-pengawas"></select>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="id_jam_reject" class="form-label">Jam Ke -</label>
                                                                <select name="jam" id="id_jam_reject" class="form-control form-control-sm">
                                                                    <option value="">Pilih Jam Kerja</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                    <option value="6">6</option>
                                                                    <option value="7">7</option>
                                                                    <option value="8">8</option>
                                                                    <option value="9">9</option>
                                                                    <option value="10">10</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 border p-3 m-2">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="input-group-sm mb-2">
                                                                        <label for="id_jam_reject" class="form-label">Defect</label>
                                                                        <select name="jenis" id="id_jam_reject" class="form-control">
                                                                            <option value="Open Bonding">Open Bonding</option>
                                                                            <option value="Over Cement">Over Cement</option>
                                                                            <option value="Over Primer">Over Primer</option>
                                                                            <option value="Solelaying">Solelaying</option>
                                                                            <option value="Dirty">Dirty</option>
                                                                            <option value="Different Color">Different Color</option>
                                                                            <option value="Top Gauge">Top Gauge</option>
                                                                            <option value="Off Center">Off Center</option>
                                                                            <option value="Attaching">Attaching</option>
                                                                            <option value="Damage Material">Damage Material</option>
                                                                            <option value="Painting">Painting</option>
                                                                            <option value="Other/Trimming">Other/Trimming</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group-sm mb-2">
                                                                        <label for="date_id" class="form-label">Date</label>
                                                                        <input type="date" class="form-control form-control-sm" name="date" id="id_date_reject" aria-describedby="dateID">
                                                                    </div>
                                                                    <div class="input-group-sm mb-2">
                                                                        <label for="id_qty_reject" class="form-label">QTY</label>
                                                                        <input type="number" class="form-control form-control-sm reset" name="qty" id="id_qty_reject" aria-describedby="qtyID">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="export" role="tabpanel" aria-labelledby="export-tab">
                                <div class="col-12 card">
                                    <div class="card-header">
                                        <h5 class="fw-bold">Export to Excel</h5>
                                    </div>
                                    <div class="card body p-2">
                                        <form method="POST" action="output_stf/print" id="print-stf" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-2 col-4">
                                                <label for="exampleInputPassword1" class="form-label">Option</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="option" value="input" id="radioOption1" checked>
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                      INPUT
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="option" value="output" id="radioOption2">
                                                    <label class="form-check-label" for="flexRadioDefault2">
                                                      OUTPUT
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="option" value="reject" id="radioOption3">
                                                    <label class="form-check-label" for="flexRadioDefault3">
                                                      REJECT
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="mb-2 col-4">
                                                <label for="exampleInputPassword1" class="form-label">Date</label>
                                                <input type="date" class="form-control" name="date" id="month_export">
                                            </div>
                                            <button class="btn btn-primary">PRINT</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row">
                        <div class="form-group col-3 card p-2 m-2" style="font-size:80%">
                            <div class="row">
                                <div class="col-5">
                                    <label for="exampleInputEmail1">Pilih Tanggal</label>
                                    <input type="date" value="{{ date('Y-m-d') }}" name="data_date" id="id_data_date" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-5">
                                    <label for="Pilih Shift">Shift</label>
                                    <select name="data_shift" id="id_data_shift" class="form-control">
                                        <option value="">PILIH SHIFT</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="NON SHIFT">NON SHIFT</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-2 card p-2 m-2" style="font-size:80%">
                            <label for="exampleInputEmail1">Pilih Display</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    All
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    PerLine
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-2 card p-2 m-2" id="fg_pilih_line" style="font-size:80%; display:none">
                            <label for="exampleInputEmail1">Pilih Pengawas</label>
                            <select name="name_pilih_line" class="select-pengawas" id="id_pilih_pengawas" class="form-cotnrol">
                            </select>
                        </div>
                        @auth
                            @if (auth()->user()->role_id==7 || auth()->user()->role_id==1 || auth()->user()->role_id==5 || auth()->user()->role_id==8)
                                <div class="form-group col-2 card p-2 m-2 row" id="btn_pilih_line" style="font-size:80%; display:none">
                                    <button type="button" onclick="openWIP()" class="btn btn-info">WIP</button>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="table table-striped text-center" id="display_all" style="font-size: 80%">
                                <thead>
                                    <tr>
                                        <th class="align-middle" rowspan="3">Option</th>
                                        <th class="align-middle" rowspan="3">Pengawas</th>
                                        <th class="align-middle" colspan="20">Output (Jam Kerja)</th>
                                        <th class="align-middle" rowspan="3">Total Output</th>
                                        <th class="align-middle" rowspan="3">Achive</th>
                                        <th class="align-middle bg-danger" rowspan="3">RFT</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">1</th>
                                        <th colspan="2">2</th>
                                        <th colspan="2">3</th>
                                        <th colspan="2">4</th>
                                        <th colspan="2">5</th>
                                        <th colspan="2">6</th>
                                        <th colspan="2">7</th>
                                        <th colspan="2" class="bg-danger">8</th>
                                        <th colspan="2" class="bg-danger">9</th>
                                        <th colspan="2" class="bg-danger">10</th>
                                    </tr>
                                    <tr>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                        <th>Output</th>
                                        <th class="bg-danger">RFT</th>
                                    </tr>
                                </thead>
                                <tbody id="tb_data"></tbody>
                        </table>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="table table-hover text-center" id="display_perLine" style="font-size: 80%; display:none">
                            <thead>
                                <tr>
                                    <th class="align-middle">Jam Kerja</th>
                                    <th class="align-middle bg-success">Input</th>
                                    <th class="align-middle bg-primary">Output</th>
                                    <th class="align-middle bg-danger">Reject</th>
                                    <th class="align-middle bg-danger">RFT</th>
                                    <th class="align-middle bg-secondary" onclick="openDetailPerDay()">BTS</th>
                                </tr>
                            </thead>
                            <tbody id="tb_perCell"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div id="pageMessages"></div>
<div id="loading-spinner" style="display:none">
    <div class="loader"></div>
</div>
<div class="modal fade" id="modal-show-data-detail-perline" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">List Data Detail PerLine </h5><p class="fw-bold" id="title-modal-detail-perLine"></p>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="delete-modal-detailperline" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="form" id="form-modal-detail-perline">
                    <input type="hidden" name="jam" id="jam-modal-input-perLine">
                    <input type="hidden" name="date" id="date-modal-input-perLine">
                    <input type="hidden" name="pengawas" id="pengawas-modal-input-perLine">
                    <input type="hidden" name="shift" id="shift-modal-input-perLine">
                    <div class="row mb-2">
                        <div class="col-3 card">
                            <p>Jam: </p><h4 class="fw-bold text-center" id="jam-modal-detail-perline"></h4>
                        </div>
                        <div class="col-3 card">
                            <p>Date: </p><h4 class="fw-bold text-center" id="date-modal-detail-perline"></h4>
                        </div>
                        <div class="col-3 card">
                            <p>Pengawas: </p><h4 class="fw-bold text-center" id="pengawas-modal-detail-perline"></h4>
                        </div>
                        <div class="col-3 card">
                            <p>Shift: </p><h4 class="fw-bold text-center" id="shift-modal-detail-perline"></h4>
                        </div>
                    </div>
                    <div style="overflow-x:auto;" id="detail-perline">

                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                @auth
                    @if (auth()->user()->role_id==7 || auth()->user()->role_id==1 || auth()->user()->role_id==5 || auth()->user()->role_id==8)
                        <button type="submit" class="btn btn-danger">Delete</button>
                    @endif
                @endauth
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
@include('stockfit.stockfit_line.script.script_output_stockfit')
@endsection

