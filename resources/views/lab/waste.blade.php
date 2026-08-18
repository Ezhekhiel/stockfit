@extends('layouts.main')
@section('content')
    <style>
        #search_id {
            width: 40ch;
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
                            <footer class="blockquote-footer">Adhesive Waste Data System</footer>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Action Bar & Inputs -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <!-- Group Tombol -->
                            <button type="button" id="inputButton" class="btn btn-warning">INPUT</button>
                            <button type="button" id="exportButton" class="btn btn-secondary">Export</button>

                            <input type="text" id="search_date_range"
                                class="form-control text-center w-auto style-input-flex date_range"
                                onchange="onchangeMain()" placeholder="Select date range">


                            <input type="text" name="search" id="search_id" onfocus="this.value=''"
                                onkeyup="searchMain()" placeholder="Search Code Chemical dan Model"
                                class="form-control text-center">

                            <select name="select_option" id="select_option" onchange="main(this.value)"
                                class="form-select text-center w-auto style-input-flex">
                                <option value="Building-1">Building-1</option>
                                <option value="Building-2">Building-2</option>
                                <option value="Stockfit">Stockfit</option>
                            </select>
                        </div>
                        <div class="overflow-auto" style="max-height: 900px;">
                            <table class="table text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Code Chemical</th>
                                        <th>Model</th>
                                        <th>Adhesive Supplier</th>
                                        <th>Type of Adhesive</th>
                                        <th>Adhesive Kind</th>
                                        <th>Adhesive Usage Quantity (Gram)</th>
                                        <th>Adhesive Lot Number</th>
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
    <!-- Modal Mixing -->
    <div id="pageMessages"></div>

    <div class="modal fade" id="input_modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="#" id="form-input-modal" target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">INPUT DATA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="id_area_option" class="form-label">Option Area</label>
                                <select name="area" id="id_area_option" class="form-select">
                                    <option value="">Select Area</option>
                                    <option value="Building-1">Building-1</option>
                                    <option value="Building-2">Building-2</option>
                                    <option value="Stockfit">Stockfit</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="id_gramasi_modal" class="form-label">Gramasi</label>
                                <input type="text" name="gram" id="id_gramasi_modal" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="id_lot_number_modal" class="form-label">LOT Number</label>
                                <input type="number" name="lot_number" id="id_lot_number_modal" class="form-control">
                            </div>
                            <div class="col-12">
                                <div class="card p-3">
                                    <!-- HEADER & SEARCH TABEL 1 -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-12 text-center">
                                            <h3 class="fw-bold mb-0">Data BSOM</h3>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="search_data" class="form-label mb-1 fw-bold">Search
                                                Model</label>
                                            <input type="text" name="model" id="search_data"
                                                class="form-control form-control-sm">
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
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="description_id" class="form-label">Description</label>
                                <textarea name="description" id="description_id" cols="30" rows="10" class="form-control"></textarea>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="update_modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="#" id="form-update-modal" target="_blank"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <input type="hidden" name="id" id="id_update">
                            <div class="col-md-2">
                                <label for="id_gramasi_modal" class="form-label">Code Chemical</label>
                                <input type="text" disabled name="code_chemical" id="id_update_code_chemical"
                                    class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="id_gramasi_modal" class="form-label">Model</label>
                                <input type="text" disabled name="code_chemical" id="id_update_model"
                                    class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="id_gramasi_modal" class="form-label">Adhesive Lot Number</label>
                                <input type="text" disabled name="code_chemical" id="id_update_lot_number"
                                    class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="id_gramasi_modal" class="form-label">Gramasi</label>
                                <input type="text" name="gram" id="id_update_gram" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="id_gramasi_modal" class="form-label">Description</label>
                                <textarea name="description" id="id_update_description" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="btn-delete-update">Delete</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="export_modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="#" id="form-export-modal" target="_blank"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="id_gramasi_modal" class="form-label">Pilih Range Data</label>
                                <input type="text" class="form-control date_range" placeholder="Select date range">
                            </div>
                            <div class="col-md-2">
                                <label for="id_gramasi_modal" class="form-label">Area</label>
                                <select name="select_option" id="select_area_export"
                                    class="form-select text-center w-auto style-input-flex">
                                    <option value="Building-1">Building-1</option>
                                    <option value="Building-2">Building-2</option>
                                    <option value="Stockfit">Stockfit</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" id="btnExport" class="btn btn-success mt-2">
                            Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('lab.script.script_waste')
@endsection
