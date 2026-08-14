@extends('layouts.index')

@section('content')
    <br>
    <style>
        .rack {
            height: 300px;
            border: 3px solid black;
        }

        .tableFixHeadForm {
            overflow-y: auto;
            height: 500px;
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
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ $errors->first() }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card bg-dark">
                    <div class="card-header">
                        <div class="text-center">
                            <p class="h4 mb-3 fw-bold">CHEMICAL</p>
                            <footer class="blockquote-footer">Mixing Glue System</footer>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <button type="button" onclick="openDatabaseChemical()"
                                class="btn btn-info col-xl-2 col-lg-2 col-md-6 col-sm-6 m-1 mb-3">Database</button>
                            <button type="button" id="addBarcode"
                                class="btn btn-primary col-xl-2 col-lg-2 col-md-6 col-sm-6 m-1 mb-3">Print Barcode</button>
                            <button type="button" id="mixingButton"
                                class="btn btn-warning col-xl-2 col-lg-2 col-md-6 col-sm-6 m-1 mb-3">MIXING</button></button>
                            <button type="button" id="exportButton"
                                class="btn btn-secondary col-xl-1 col-lg-1 col-md-6 col-sm-6 m-1 mb-3">Export</button>
                            <input type="text" name="search" id="search_data" placeholder="Scan Hire"
                                class="text-center form-control col-xl-2 col-sm-6 m-1 mb-4">
                            <input type="text" name="search_model" id="search_data_model" onfocus="this.value=''"
                                placeholder="Search Model" class="text-center form-control col-xl-2 col-sm-6 m-1 mb-4">
                            <input type="hidden" name="link" id="link_"
                                value="{{ url('/tooling/pad_press_stockfit/scan/') }}">
                        </div>
                        <div style="overflow-x:auto;" class="px-2 tableFixHead">
                            <table class="table text-center">
                                <thead class="bg-dark">
                                    <tr>
                                        <th class="align-middle" colspan="2">No</th>
                                        <th class="align-middle">ID Barcode</th>
                                        <th class="align-middle">Line / Cell</th>
                                        <th class="align-middle">Model's</th>
                                        <th class="align-middle">Adhesive Supplier</th>
                                        <th class="align-middle">Type of Adhesive</th>
                                        <th class="align-middle">Adhesive Name</th>
                                        <th class="align-middle">Adhesive Kind</th>
                                        <th class="align-middle">Adhesive Usage Quantity (Gram)</th>
                                        <th class="align-middle">Adhesive Lot Number</th>
                                        <th class="align-middle">Mixing Time</th>
                                        <th class="align-middle">Expires on</th>
                                        @auth
                                            <th class="align-middle">Action</th>
                                        @endauth
                                    </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div id="pageMessages"></div>
    <div class="modal fade" id="openModalExport" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sd" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">EXPORT DATA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ url('/lab/chemical/export') }}" id="form-export-main" name="formInput"
                        target="_blank" enctype="multipart/form-data">
                        @csrf
                        <input type="month" name="month" id="month-id-modal" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Export</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateData" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">UPDATE DATA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="form-update-main" name="formInput" enctype="multipart/form-data">
                        @csrf
                        <li class="nav-item ml-3" id="nav-loading" style="display: none">
                            <div class="lds-dual-ring text-light"></div>
                        </li>
                        <div style="overflow-x:auto;">
                            <table class="table table-striped text-center mt-2" id="table-scan">
                                <tr>
                                    <th>ID Barcode</th>
                                    <td id="id_barcode_scan"></td>
                                </tr>
                                <tr>
                                    <th>Model</th>
                                    <td id="id_model_update"></td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td id="id_supplier_update"></td>
                                </tr>
                                <tr>
                                    <th>Component</th>
                                    <td id="id_component_update"></td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td id="id_type_update"></td>
                                </tr>
                                <tr>
                                    <th>Code Chemical</th>
                                    <td><input type="text" readonly name="code_chemical" id="id_code_update"
                                            class="form-control text-center"></td>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <td>
                                        <select name="line" id="line_option_update"
                                            class="form-control text-center option_update">
                                        </select>
                                        <button type="submit" id="btn_finish_update"
                                            class="btn btn-primary btn-block">Finish</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addBarcode_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Barcode Normal Print</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('/lab/chemical/print_barcode') }}" id="form-print-modal"
                        target="_blank" enctype="multipart/form-data">
                        @csrf
                        <input type="number" name="total" id="total_id_print_barcode" autocomplete="off"
                            class="form-control" placeholder="How many barcodes do you want to print?">
                        <br>
                        <input type="text" name="qty" id="qty_print_id_normal" class="form-control"
                            placeholder="QTY PRINT'">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Print</button>
                    </form>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Custome Barcode Print</h5>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('/lab/chemical/custome_print_barcode') }}" id="form-print-modal"
                        target="_blank" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <input type="text" name="total_custome" id="total_id_print_barcode"
                                    class="form-control" placeholder="Example '251-252-253-254'">
                                <small class="text-danger">Numbers are separated by a dash (-)</small>
                            </div>
                            <div class="col-12">
                                <label for="qty_print_id">QTY Print</label>
                                <input type="text" name="qty" id="qty_print_id" class="form-control"
                                    placeholder="QTY PRINT'">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Print</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mixing_modal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">MIXING</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="#" id="form-mixing-modal" target="_blank"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="id_barcode_modal" class="form-label">ID Barcode</label>
                                    <input type="text" name="id_barcode" id="id_barcode_modal" class="form-control"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="id_barcode_modal_select" class="form-label">Option Area</label>
                                    <select name="line_select" id="id_area_option" class="form-select">
                                        <option value="">Select Option</option>
                                        <option value="C2B">C2B</option>
                                        <option value="Stockfit">Stockfit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="id_barcode_modal_select" class="form-label">Line Select</label>
                                    <select name="line_select" id="id_line_select" class="form-select">
                                        <option value="">Select Line</option>
                                        <option value="LINE - 1A">LINE - 1A</option>
                                        <option value="LINE - 1B">LINE - 1B</option>
                                        <option value="LINE - 2A">LINE - 2A</option>
                                        <option value="LINE - 2B">LINE - 2B</option>
                                        <option value="LINE - 3A">LINE - 3A</option>
                                        <option value="LINE - 3B">LINE - 3B</option>
                                        <option value="LINE - 4A">LINE - 4A</option>
                                        <option value="LINE - 4B">LINE - 4B</option>
                                        <option value="LINE - 5A">LINE - 5A</option>
                                        <option value="LINE - 5B">LINE - 5B</option>
                                        <option value="LINE - 6A">LINE - 6A</option>
                                        <option value="LINE - 6B">LINE - 6B</option>
                                        <option value="LINE - 7A">LINE - 7A</option>
                                        <option value="LINE - 7B">LINE - 7B</option>
                                        <option value="LINE - 8A">LINE - 8A</option>
                                        <option value="LINE - 8B">LINE - 8B</option>
                                        <option value="LINE - 9A">LINE - 9A</option>
                                        <option value="LINE - 9B">LINE - 9B</option>
                                        <option value="LINE - 10A">LINE - 10A</option>
                                        <option value="LINE - 10B">LINE - 10B</option>
                                        <option value="LINE - 11A">LINE - 11A</option>
                                        <option value="LINE - 11B">LINE - 11B</option>
                                        <option value="LINE - 12A">LINE - 12A</option>
                                        <option value="LINE - 12B">LINE - 12B</option>
                                        <option value="LINE - 13A">LINE - 13A</option>
                                        <option value="LINE - 13B">LINE - 13B</option>
                                        <option value="LINE - 14A">LINE - 14A</option>
                                        <option value="LINE - 14B">LINE - 14B</option>
                                        <option value="LINE - 15A">LINE - 15A</option>
                                        <option value="LINE - 15B">LINE - 15B</option>
                                        <option value="LINE - 16A">LINE - 16A</option>
                                        <option value="LINE - 16B">LINE - 16B</option>
                                        <option value="LINE - 17A">LINE - 17A</option>
                                        <option value="LINE - 17B">LINE - 17B</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="id_barcode_modal" class="form-label">LINE</label>
                                    <input type="text" name="line" id="id_line_modal" class="form-control"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="id_minutes" class="form-label">Expired (minutes)</label>
                                    <select name="minutes" id="id_minutes" class="form-control">
                                        <option value="-">-</option>
                                        <option value="60">60</option>
                                        <option value="120">120</option>
                                        <option value="240">240</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="mb-3">
                                    <label for="id_minutes" class="form-label">Threatment Adhesive</label>
                                    <select name="option" id="threatment_select" class="form-control">
                                        <option value="Bowl">Bowl</option>
                                        <option value="Pressure Tank">Pressure Tank</option>
                                        <option value="Gravity Feed">Gravity Feed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card p-4">
                                    <div style="overflow-x:auto;" class="px-2 tableFixHeadForm">
                                        <div class="row">
                                            <div class="col-2">
                                                <label for="model_search_mixing" class="form-label">Search Model</label>
                                                <input type="text" name="model" id="model_search_mixing"
                                                    class="form-control mb-2">
                                            </div>
                                            <div class="col-10 text-center">
                                                <h3 class="text-bold">Data BSOM</h3>
                                            </div>
                                        </div>
                                        <table class="table text-center">
                                            <thead class="bg-dark">
                                                <tr>
                                                    <th></th>
                                                    <th>Code Chemical</th>
                                                    <th>Model</th>
                                                    <th>Supplier</th>
                                                    <th>Component</th>
                                                    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_database"></tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        <a class="text-dark" data-toggle="collapse" href="#showAct" role="button"
                                            aria-expanded="false" aria-controls="collapseExample">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                    <div class="collapse" id="showAct">
                                        <div style="overflow-x:auto;" class="px-2 tableFixHeadForm mt-4">
                                            <div class="row">
                                                <div class="col-2">
                                                    <label for="model_search_mixing_act" class="form-label">Search
                                                        Chemical</label>
                                                    <input type="text" name="model" id="model_search_mixing_act"
                                                        class="form-control mb-2" autocomplete="off">
                                                </div>
                                                <div class="col-10 text-center">
                                                </div>
                                            </div>
                                            <table class="table text-center">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th></th>
                                                        <th>Code Chemical</th>
                                                        <th>Model</th>
                                                        <th>Supplier</th>
                                                        <th>Component</th>
                                                        <th>Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_database_act"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="id_gramasi_modal" class="form-label">Gramasi</label>
                                    <input type="text" name="gram" id="id_gramasi_modal" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="id_lot_number_modal" class="form-label">LOT Number</label>
                                    <input type="number" name="lot_number" id="id_lot_number_modal"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">SAVE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('lab.script.script_lab')
@endsection
