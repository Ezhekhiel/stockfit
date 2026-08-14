@extends('layouts.index')

@section('content')
<br>
<style>
    .rack{
        height: 300px;
        border: 3px solid black;
    }
    .tableFixHeadForm {
        overflow-y: auto;
        height: 300px;
    }

    .tableFixHeadForm table {
        border-collapse: collapse;
        width: 100%;
    }
    .tableFixHeadForm th,
    .tableFixHeadForm td {
        padding: 8px 16px;
    }
    .tableFixHeadForm thead {
        position: sticky;
        top: 0;
        z-index: 3;
        background: #eee;
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <section class="col-lg-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <p class="h4 mb-3 fw-bold">TOOLING MANAGE DATABASE</p>
                        <footer class="blockquote-footer">PT. PARKLANDWORLD INDONESIA 2</footer>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card m-2">
                        <div class="row m-4"style="height: 30vh">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Manage Model
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="#" id="form_manage_model" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="inpt_manage_model">Model</label>
                                                <input type="hidden" class="resetModel" id="inpt_manage_id_model" name="id">
                                                <input type="text" class="form-control resetModel" id="inpt_manage_model" style="text-transform: uppercase;" placeholder="Model" name="model">
                                            </div>
                                            <button type="submit" id="updateButtonModel" class="btn btn-info mt-2" style="display: none">Update</button>
                                            <button type="button" id="cancleButtonModel" class="btn btn-danger mt-2" style="display: none">Cancle</button>
                                            <button type="submit" id="insertButtonModel" class="btn btn-primary mt-2">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Model List
                                    </div>
                                    <div class="card-body" style="height: 25vh">
                                        <div style="overflow-x:auto;" class="px-2 tableFixHeadForm">
                                            <table class="table text-center">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Model</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_model"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card m-2">
                        <div class="row m-4"style="height: 30vh">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Versi Database
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="#" id="form_manage_versi" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="inpt_manage_id_versi">Versi</label>
                                                <input type="hidden" class="resetVersi" id="inpt_manage_id_versi" name="id">
                                                <input type="text" class="form-control resetVersi" id="inpt_manage_versi" style="text-transform: uppercase;" placeholder="Versi" name="versi">
                                            </div>
                                            <button type="submit" id="updateButtonVersi" class="btn btn-info mt-2" style="display: none">Update</button>
                                            <button type="button" id="cancleButtonVersi" class="btn btn-danger mt-2" style="display: none">Cancle</button>
                                            <button type="submit" id="insertButtonVersi" class="btn btn-primary mt-2">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Versi List
                                    </div>
                                    <div class="card-body" style="height: 25vh">
                                        <div style="overflow-x:auto;" class="px-2 tableFixHeadForm">
                                            <table class="table text-center">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Versi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_versi"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card m-2">
                        <div class="row m-4"style="height: 30vh">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Remark Database
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="#" id="form_manage_remark" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="inpt_manage_remark">Remark</label>
                                                <input type="hidden" class="resetRemark" id="inpt_manage_id_remark" name="id">
                                                <input type="text" class="form-control resetRemark" id="inpt_manage_remark" style="text-transform: uppercase;" placeholder="Remark" name="remark">
                                            </div>
                                            <button type="submit" id="updateButtonRemark" class="btn btn-info mt-2" style="display: none">Update</button>
                                            <button type="button" id="cancleButtonRemark" class="btn btn-danger mt-2" style="display: none">Cancle</button>
                                            <button type="submit" id="insertButtonRemark" class="btn btn-primary mt-2">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Remark List
                                    </div>
                                    <div class="card-body" style="height: 25vh">
                                        <div style="overflow-x:auto;" class="px-2 tableFixHeadForm">
                                            <table class="table text-center">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_remark"></tbody>
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
<div id="loading-spinner" style="display:none">
    <div class="loader"></div>
</div>
<div id="pageMessages"></div>
<div class="modal fade" id="addBarcode_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Barcode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/tooling/pad_press_stockfit/print_barcode') }}" id="form-print-modal" target="_blank" enctype="multipart/form-data">
                    @csrf
                    <input type="number" name="total" id="total_id_print_barcode" class="form-control" placeholder="How many barcodes do you want to print?">
                    <small class="text-danger">Quantity / Pair</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Print</button>
            </form>
            </div>
        </div>
    </div>
</div>
@include('tooling.scripts.manage')
@endsection

