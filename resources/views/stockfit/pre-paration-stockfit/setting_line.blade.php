@extends('layouts.index')

@section('content')
<br>
<div class="container-fluid">
    <div class="row justify-content-center">
        <section class="col-lg-12 connectedSortable">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="text-center">
                        <p class="h4 mb-3 fw-bold">STOCKFIT SETTING LINE</p>
                        <footer class="blockquote-footer">STOCKFIT SETTING LINE</footer>
                    </div>
                </div>
                @auth
                    @if (auth()->user()->role_id==7 || auth()->user()->role_id==1 || auth()->user()->role_id==5 || auth()->user()->role_id==8)
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="input-tab" data-toggle="tab" href="#input" role="tab" aria-controls="input" aria-selected="false">SETTING</a>
                                </li>
                                <li class="nav-item mr-auto">
                                    <a class="nav-link" id="output-tab" data-toggle="tab" href="#output" role="tab" aria-controls="output" aria-selected="false">TRANSFER</a>
                                </li>
                                <li class="nav-item mr-4">
                                    <a class="nav-link" id="export-tab" data-toggle="tab" href="#export" role="tab" aria-controls="export" aria-selected="false">Export</a>
                                </li>
                            </ul>
                        </div>
                        {{-- menampilkan error validasi --}}
                            @if (count($errors) > 0)
                                <div class="card-body">
                                    <div class="alert alert-danger alert-dismissible fade show">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <br/>
                        <!-- form validasi -->
                        <div class="card-body">
                            <div class="col-12 justify-content-center">
                                <div class="card col-12 p-2 mt-2">
                                    <div class="row justify-content-center">
                                        <div class="col-1 text-center">
                                            <label for="jam_7" class="form-label">Jam Ke - 1</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_1" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_1"></div>
                                            <input type="hidden" id="form_jam_1">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_8" class="form-label">Jam Ke - 2</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_2" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_2"></div>
                                            <input type="hidden" id="form_jam_2">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_9" class="form-label">Jam Ke - 3</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_3" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_3"></div>
                                            <input type="hidden" id="form_jam_3">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_10" class="form-label">Jam Ke - 4</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_4" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_4"></div>
                                            <input type="hidden" id="form_jam_4">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_11" class="form-label">Jam Ke - 5</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_5" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_5"></div>
                                            <input type="hidden" id="form_jam_5">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_12" class="form-label"></label>
                                            <h5 class="font-weight-bold">ISTIRAHAT</h5>
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_13" class="form-label">Jam Ke - 6</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_6" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_6"></div>
                                            <input type="hidden" id="form_jam_6">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_14" class="form-label">Jam Ke - 7</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_7" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_7"></div>
                                            <input type="hidden" id="form_jam_7">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_15" class="form-label">Jam Ke - 8</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_8" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_8"></div>
                                            <input type="hidden" id="form_jam_8">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_16" class="form-label">Jam Ke - 9</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_9" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_9"></div>
                                            <input type="hidden" id="form_jam_9">
                                        </div>
                                        <div class="col-1 text-center">
                                            <label for="jam_17" class="form-label">Jam Ke - 10</label>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" id="jam_10" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="display-progress-bar" style="font-size:95%" id="display_jam_10"></div>
                                            <input type="hidden" id="form_jam_10">
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-center p-4">
                                        <div class="col col-md-2 text-center">
                                            <label class="mt-2" style="font-size: 80%">TRANSFER</label>
                                            <div class="progress">
                                                <div class="progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <div class="col col-md-2 text-center">
                                            <label class="mt-2" style="font-size: 80%">TRANSFER TIDAK SESUAI TARGET</label>
                                            <div class="progress">
                                                <div class="progress-bar-striped progress-bar-animated bg-dark" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <div class="col col-md-2 text-center">
                                            <label class="mt-2" style="font-size: 80%">SPK = 100%</label>
                                            <div class="progress">
                                                <div class="progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <div class="col col-md-2 text-center">
                                            <label class="mt-2" style="font-size: 80%">50% < SPK > 100%</label>
                                            <div class="progress">
                                                <div class="progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <div class="col col-md-2 text-center">
                                            <label class="mt-2" style="font-size: 80%">SPK < 50%</label>
                                            <div class="progress">
                                                <div class="progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade" id="input" role="tabpanel" aria-labelledby="input-tab">
                                        <div class="col-12 card">
                                            <div class="card-header">
                                                <h5 class="fw-bold text-center">Input Setting Line</h5>
                                            </div>
                                            <div class="card-body">
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
                                                                        <label for="id_shift_input" class="form-label">Shift</label>
                                                                        <select name="shift" id="id_shift_input" class="form-control form-control-sm">
                                                                            <option>Pilih Shift</option>
                                                                            <option value="A">A</option>
                                                                            <option value="B">B</option>
                                                                            <option value="NON SHIFT">NON SHIFT</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="date_id" class="form-label">Date</label>
                                                                        <input type="date" class="form-control form-control-sm" name="date" id="id_date_input" aria-describedby="dateID" value="{{ date("Y-m-d") }}">
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="id_pengawas_input" class="form-label">Pengawas</label>
                                                                        <select name="pengawas" id="id_pengawas_input" class="form-control form-control-sm select-pengawas"></select>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="id_line_input" class="form-label">Line</label>
                                                                        <select name="line" id="id_line_input" class="form-control form-control-sm">
                                                                            <option value="">Pilih Line</option>
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
                                                                            <option value="Stengah Jadi-1">Stengah Jadi-1</option>
                                                                            <option value="Stengah Jadi-2">Stengah Jadi-2</option>
                                                                            <option value="Stengah Jadi-3">Stengah Jadi-3</option>
                                                                            <option value="Stengah Jadi-4">Stengah Jadi-4</option>
                                                                            <option value="Stengah Jadi-5">Stengah Jadi-5</option>
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
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="output" role="tabpanel" aria-labelledby="output-tab">
                                        <div class="col-12 card">
                                            <div class="card-header">
                                                <h5 class="fw-bold text-center">Output Setting Line</h5>
                                            </div>
                                                <div class="card-body">
                                                    <form method="POST" action="#" id="form_output" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row justify-content-center" style="font-size: 80%">
                                                            <div class="col-3 border p-3 m-2">
                                                                <div class="card p-2 mt-4">
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
                                                                        <label for="date_id" class="form-label">Date</label>
                                                                        <input type="date" class="form-control form-control-sm" name="date" id="id_date_output" aria-describedby="dateID" value="{{ date("Y-m-d") }}">
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="id_pengawas_output" class="form-label">Pengawas</label>
                                                                        <select name="pengawas" id="id_pengawas_output" class="form-control form-control-sm select-pengawas"></select>
                                                                    </div>
                                                                    {{-- <div class="mb-2">
                                                                        <label for="id_line_output" class="form-label">Line</label>
                                                                        <select name="line" id="id_line_output" class="form-control form-control-sm">
                                                                            <option value="">Pilih Line</option>
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
                                                                            <option value="Stengah Jadi-1">Stengah Jadi-1</option>
                                                                            <option value="Stengah Jadi-2">Stengah Jadi-2</option>
                                                                            <option value="Stengah Jadi-3">Stengah Jadi-3</option>
                                                                            <option value="Stengah Jadi-4">Stengah Jadi-4</option>
                                                                            <option value="Stengah Jadi-5">Stengah Jadi-5</option>
                                                                        </select>
                                                                    </div> --}}
                                                                </div>
                                                            </div>
                                                            <div class="col-8 border p-3 m-2">
                                                                <input type="hidden" name="statusShow" id="statusShow" value="0">
                                                                <div style="overflow-x:auto;" class="tableFixHead">
                                                                    <table class="table table-striped table-hover text-center" id="resultSearch">
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="2">Jam</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="detailLineOutput"></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
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
                                                <form method="POST" action="setting_line/print" id="print-stf" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-2 col-4">
                                                        <label for="exampleInputPassword1" class="form-label">Option</label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="option" value="SHIFT A" id="radioOption1" checked>
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                            SHIFT A DAN NON SHIFT
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="option" value="SHIFT B" id="radioOption2">
                                                            <label class="form-check-label" for="flexRadioDefault2">
                                                            SHIFT B
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
                        </div>
                    @endif
                @endauth
            </div>
        </section>
    </div>
</div>
<div id="pageMessages"></div>
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="form-update-modal" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputPassword1">Pengawas</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="old_pengawas" id="id_nama_pengawas_modal" readonly>
                                <input type="hidden" name="old_nik" id="id_nik_pengawas_modal" class="form-control">
                                <small id="passwordHelpBlock" class="form-text text-danger">
                                    Database
                                </small>
                            </div>
                            <div class="col-6">
                                <select name="pengawas" id="list_id_pengawas_modal" class="form-control form-update"></select>
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                    Data Update
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Shift</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="old_shift" id="id_shift_modal" readonly>
                                <small id="passwordHelpBlock" class="form-text text-danger">
                                    Database
                                </small>
                            </div>
                            <div class="col-6">
                                <select name="shift" class="form-control form-update">
                                    <option>Pilih Shift</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="NON SHIFT">NON SHIFT</option>
                                </select>
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                    Data Update
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Date</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="old_date" id="id_date_modal" readonly>
                                <small id="passwordHelpBlock" class="form-text text-danger">
                                    Database
                                </small>
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control form-update" name="date">
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                    Data Update
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Jam Ke -</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="old_jam" id="id_jam_modal" readonly>
                                <small id="passwordHelpBlock" class="form-text text-danger">
                                    Database
                                </small>
                            </div>
                            <div class="col-6">
                                <select name="jam" class="form-control form-control-sm form-update">
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
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                    Data Update
                                </small>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="exampleInputPassword1">Line</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="old_line" id="id_line_modal" readonly>
                                <small id="passwordHelpBlock" class="form-text text-danger">
                                    Database
                                </small>
                            </div>
                            <div class="col-6">
                                <select name="line" class="form-control form-control-sm">
                                    <option value="">Pilih Line</option>
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
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                    Data Update
                                </small>
                            </div>
                        </div>
                    </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
            </div>
        </div>
    </div>
</div>
@include('stockfit.pre-paration-stockfit.script.script_setting_line')
@endsection

