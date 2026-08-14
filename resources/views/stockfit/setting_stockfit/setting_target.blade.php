@extends('layouts.index')

@section('content')
<br>
<div class="container-fluid">
    <div class="row justify-content-center">
        <section class="col-lg-12 connectedSortable">
            <div class="card">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="setTarget-tab" data-toggle="tab" href="#setTarget" role="tab" aria-controls="setTarget" aria-selected="false">SET TARGET</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="setTarget" role="tabpanel" aria-labelledby="setTarget-tab">
                        <div class="col-12 card">
                            <div class="card-header">
                                <h5 class="fw-bold">Target Per Day</h5>
                            </div>
                            <div class="card body">
                                <div class="row">
                                    <div class="col-2 p-3">
                                        <form method="POST" action="#" id="save-target" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-2">
                                              <label for="exampleInputPassword1" class="form-label">NIK</label>
                                              <input type="number" class="form-control clickReset" name="nik" id="nik-target" readonly>
                                            </div>
                                            <div class="mb-2">
                                                <label for="exampleInputPassword1" class="form-label">Nama</label>
                                                <input type="text" class="form-control clickReset" id="nama-target" readonly>
                                            </div>
                                            <div class="mb-2">
                                                <label for="exampleInputPassword1" class="form-label">Date</label>
                                                <input type="text" class="form-control clickReset" name="date" id="date-target" readonly>
                                            </div>
                                            <div class="mb-2">
                                                <label for="exampleInputPassword1" class="form-label">Shift</label>
                                                <input type="text" class="form-control clickReset" name="shift" id="shift-target" readonly>
                                            </div>
                                            <div class="mb-2">
                                                <label for="exampleInputPassword1" class="form-label">Jam</label>
                                                <input type="text" class="form-control clickReset" name="jam" id="jam-target" readonly>
                                            </div>
                                            <div class="mb-2">
                                                <label for="exampleInputPassword1" class="form-label">Target</label>
                                                <input type="text" class="form-control clickReset" name="target" id="value-target">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update Target</button>
                                        </form>
                                    </div>
                                    <div class="col-10 p-3">
                                        <div class="col-4 mb-3">
                                            <div class="mb-3 row">
                                                <label for="staticDate" class="col-sm-3 col-form-label">Date :</label>
                                                <div class="col-sm-9">
                                                  <input type="date" onchange="main()" class="form-control" value="{{ date('Y-m-d') }}" id="date_id_title">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="staticShift" class="col-sm-3 col-form-label">Shift : </label>
                                                <div class="col-sm-9">
                                                    <select id="shift_id_shift" onchange="main()" class="form-control">
                                                        <option value="A">A</option>
                                                        <option value="B">B</option>
                                                        <option value="NON SHIFT">NON SHIFT</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="staticDate" class="col-sm-3 col-form-label">Cari Nama :</label>
                                                <div class="col-sm-9">
                                                  <input type="text" onkeyup="main()" class="form-control" id="nama_id_nama">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="overflow-x:auto;max-height:400px;overflow-y:scroll;">
                                            <table class="table table-striped text-center table-hover">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Nama</th>
                                                        <th rowspan="2">SHIFT</th>
                                                        <th colspan="10">Target per Jam</th>
                                                    </tr>
                                                    <tr>
                                                        <th>1</th>
                                                        <th>2</th>
                                                        <th>3</th>
                                                        <th>4</th>
                                                        <th>5</th>
                                                        <th>6</th>
                                                        <th>7</th>
                                                        <th>8</th>
                                                        <th>9</th>
                                                        <th>10</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_target"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<div class="modal" id="modalDCInfo" tabindex="-1" role="dialog">
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
                                <th>PO</th>
                                <th>STYLE</th>
                                <th>XFD</th>
                                <th>SIZE</th>
                                <th>CELL-AWAL</th>
                                <th>CELL-UPDATE</th>
                                <th>QTY SET</th>
                                <th>JAM</th>
                                <th>STATUS MUTASI</th>
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
@include('stockfit.setting_stockfit.script.script_setting_target')
@endsection

