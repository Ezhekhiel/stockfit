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
                            <a class="nav-link active" id="registrasi-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">REGISTER</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="register" role="tabpanel" aria-labelledby="register-tab">
                        <div class="col-12 card">
                            <div class="card-header">
                                <h5 class="fw-bold">Register Pengawas</h5>
                            </div>
                            <div class="card body">
                                <div class="row p-2">
                                    <div class="col-3">
                                        <form method="POST" action="#" id="register-pengawas" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                              <label for="exampleInputPassword1" class="form-label">NIK</label>
                                              <input type="number" class="form-control clickReset" name="nik" id="nik-register">
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputPassword1" class="form-label">Nama</label>
                                                <input type="text" class="form-control clickReset" style="text-transform: uppercase" name="nama" id="nama-register">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Tambah Pengawas</button>
                                        </form>
                                    </div>
                                    <div class="col-9">
                                        <div style="overflow-x:auto;max-height:1000px;overflow-y:scroll;" class="p-3">
                                            <table class="table table-striped text-center table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>NIK</th>
                                                        <th>Nama</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_pengawas"></tbody>
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
<div class="modal fade" id="modal-update-pengawas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Data Pengawas</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="update-pengawas" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">NIK</label>
                        <input type="number" class="form-control clickReset" name="nik" id="nik-register-update">
                        <input type="hidden" class="form-control clickReset" name="id" id="id-register-update">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Nama</label>
                        <input type="text" class="form-control clickReset" name="nama" id="nama-register-update">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('stockfit.setting_stockfit.script.script_register')
@endsection

