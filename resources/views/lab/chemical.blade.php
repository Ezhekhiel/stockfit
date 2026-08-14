@extends('layouts.main')
@section('content')
    <style>
        .rack {
            height: 300px;
            border: 3px solid black;
        }
    </style>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <section class="col-lg-12 connectedSortable">
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ $errors->first() }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <div class="text-center">
                            <p class="h4 mb-3 fw-bold">CHEMICAL</p>
                            <footer class="blockquote-footer">Mixing Glue System</footer>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Action Bar & Inputs -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <!-- Group Tombol -->
                            <button type="button" onclick="openDatabaseChemical()" class="btn btn-info">Database</button>
                            <button type="button" id="addBarcode" class="btn btn-primary">Print Barcode</button>
                            <button type="button" id="mixingButton" class="btn btn-warning">MIXING</button>
                            <button type="button" id="exportButton" class="btn btn-secondary">Export</button>

                            <!-- Inputs & Select (Ditambahkan w-auto agar ukurannya menyesuaikan isi/fleksibel) -->
                            <input type="text" name="search" id="search_data" placeholder="Scan Here"
                                class="form-control text-center w-auto style-input-flex">

                            <input type="text" name="search_model" id="search_data_model" onfocus="this.value=''"
                                placeholder="Search Model" class="form-control text-center w-auto style-input-flex">

                            <select name="select_option" id="select_option"
                                class="form-select text-center w-auto style-input-flex">
                                <option value="">Select Area</option>
                                <option value="C2B">C2B</option>
                                <option value="Stockfit">Stockfit</option>
                            </select>

                            <input type="hidden" name="link" id="link_"
                                value="{{ url('/tooling/pad_press_stockfit/scan/') }}">
                        </div>

                        <div class="overflow-auto" style="max-height: 900px;">
                            <table class="table text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th colspan="2">No</th>
                                        <th>ID Barcode</th>
                                        <th>Line / Cell</th>
                                        <th>Model's</th>
                                        <th>Adhesive Supplier</th>
                                        <th>Type of Adhesive</th>
                                        <th>Adhesive Name</th>
                                        <th>Adhesive Kind</th>
                                        <th>Adhesive Usage Quantity (Gram)</th>
                                        <th>Adhesive Lot Number</th>
                                        <th>Mixing Time</th>
                                        <th>Expires on</th>
                                        <th>Treatment</th>
                                        @auth
                                            <th>Action</th>
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

    <!-- Modal Export -->
    <div class="modal fade" id="openModalExport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form method="post" action="{{ url('/lab/chemical/export') }}" id="form-export-main" name="formInput"
                    target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">EXPORT DATA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="month" name="month" id="month-id-modal" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Export</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Update Data -->
    <div class="modal fade" id="updateData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="post" id="form-update-main" name="formInput" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">UPDATE DATA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="nav-item ms-3" id="nav-loading" style="display: none">
                            <div class="lds-dual-ring text-light"></div>
                        </div>
                        <div class="table-responsive">
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
                                            class="form-select text-center option_update mb-2">
                                        </select>
                                        <button type="submit" id="btn_finish_update"
                                            class="btn btn-primary w-100">Finish</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Add Barcode -->
    <div class="modal fade" id="addBarcode_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <!-- Normal Print -->
                <form method="POST" action="{{ url('/lab/chemical/print_barcode') }}" id="form-print-modal"
                    target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Barcode Normal Print</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="number" name="total" id="total_id_print_barcode" autocomplete="off"
                            class="form-control mb-3" placeholder="How many barcodes do you want to print?">
                        <input type="text" name="qty" id="qty_print_id_normal" class="form-control"
                            placeholder="QTY PRINT">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Print</button>
                    </div>
                </form>

                <!-- Custom Print -->
                <form method="POST" action="{{ url('/lab/chemical/custome_print_barcode') }}"
                    id="form-print-modal-custom" target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-top">
                        <h5 class="modal-title">Custom Barcode Print</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="text" name="total_custome" id="total_id_print_barcode_custom"
                                    class="form-control" placeholder="Example '251-252-253-254'">
                                <small class="text-danger">Numbers are separated by a dash (-)</small>
                            </div>
                            <div class="col-12">
                                <label for="qty_print_id" class="form-label">QTY Print</label>
                                <input type="text" name="qty" id="qty_print_id" class="form-control"
                                    placeholder="QTY PRINT">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Print</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Mixing -->
    <div class="modal fade" id="mixing_modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="#" id="form-mixing-modal" target="_blank"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">MIXING</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="id_barcode_modal" class="form-label">ID Barcode</label>
                                <input type="text" name="id_barcode" id="id_barcode_modal" class="form-control"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label for="id_area_option" class="form-label">Option Area</label>
                                <select name="line_select" id="id_area_option" class="form-select">
                                    <option value="">Select Option</option>
                                    <option value="C2B">C2B</option>
                                    <option value="Stockfit">Stockfit</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="id_line_select" class="form-label">Line Select</label>
                                <select name="line_select" id="id_line_select" class="form-select">
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="id_line_modal" class="form-label">LINE</label>
                                <input type="text" name="line" id="id_line_modal" class="form-control"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label for="id_minutes" class="form-label">Expired (minutes)</label>
                                <select name="minutes" id="id_minutes" class="form-select">
                                    <option value="-">-</option>
                                    <option value="60">60</option>
                                    <option value="120">120</option>
                                    <option value="240">240</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="threatment_select" class="form-label">Treatment Adhesive</label>
                                <select name="option" id="threatment_select" class="form-select">
                                    <option value="Bowl">Bowl</option>
                                    <option value="Pressure Tank">Pressure Tank</option>
                                    <option value="Gravity Feed">Gravity Feed</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="card p-3">
                                    <!-- HEADER & SEARCH TABEL 1 -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-3">
                                            <label for="model_search_mixing" class="form-label mb-1 fw-bold">Search
                                                Model</label>
                                            <input type="text" name="model" id="model_search_mixing"
                                                class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-9 text-center">
                                            <h3 class="fw-bold mb-0">Data BSOM</h3>
                                        </div>
                                    </div>

                                    <!-- TABEL 1 -->
                                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                        <table class="table table-bordered text-center align-middle mb-0">
                                            <thead class="table-dark sticky-top">
                                                <tr>
                                                    <th width="5%"></th>
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

                                    <!-- TOMBOL TOGGLE JAVASCRIPT -->
                                    <div class="mt-3">
                                        <button type="button" id="btnToggleShowAct" class="btn btn-sm btn-secondary">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>

                                    <!-- CONTAINER TABEL 2 (Hidden by Default) -->
                                    <div id="showActContainer" style="display: none;" class="mt-3">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="row mb-2">
                                                <div class="col-md-3">
                                                    <label for="model_search_mixing_act"
                                                        class="form-label mb-1 fw-bold">Search Chemical</label>
                                                    <input type="text" name="model" id="model_search_mixing_act"
                                                        class="form-control form-control-sm" autocomplete="off">
                                                </div>
                                            </div>

                                            <!-- TABEL 2 -->
                                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                                <table class="table table-bordered text-center align-middle mb-0 bg-white">
                                                    <thead class="table-dark sticky-top">
                                                        <tr>
                                                            <th width="5%"></th>
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
                            </div>

                            <div class="col-md-6">
                                <label for="id_gramasi_modal" class="form-label">Gramasi</label>
                                <input type="text" name="gram" id="id_gramasi_modal" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="id_lot_number_modal" class="form-label">LOT Number</label>
                                <input type="number" name="lot_number" id="id_lot_number_modal" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('lab.script.script_lab')
@endsection
