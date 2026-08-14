@extends('layouts.main')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <section class="col-lg-12 connectedSortable">
                <div class="card bg-dark">
                    <div class="card-header">
                        <div class="text-center">
                            <p class="h4 mb-3 fw-bold">CHEMICAL</p>
                            <footer class="blockquote-footer">Database Chemical</footer>
                        </div>
                    </div>
                    <div class="row">
                        <button type="button" onclick="openModalAddDatabase()"
                            class="btn btn-primary col-xl-1 col-sm-6 m-3">Add Database</button>
                        <input type="text" name="search_model" id="search_data_model" onfocus="this.value=''"
                            placeholder="Search Model" class="form-control col-xl-1 col-sm-6 m-3 text-center">
                    </div>
                    <div style="overflow-x:auto;" class="px-2 tableFixHead">
                        <table class="table text-center table-striped table-hover">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="align-middle" rowspan="2">No</th>
                                    <th class="align-middle" rowspan="2">MODEL</th>
                                    <th class="align-middle" rowspan="2">Adhesive Supplier</th>
                                    <th class="align-middle" colspan="2">RUBBER</th>
                                    <th class="align-middle" colspan="2">MIDSOLE (EVA/PHYLON)</th>
                                    @auth
                                        <th class="align-middle" rowspan="2">Action</th>
                                    @endauth
                                </tr>
                                <tr>
                                    <th>PRIMER</th>
                                    <th>CEMENT</th>
                                    <th>PRIMER</th>
                                    <th>CEMENT</th>
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
    <div class="modal fade" id="modal_add_database" tabindex="-1" role="dialog">
        <div class="modal-dialog" style="max-width:70%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Database</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="#" id="form_add_database" enctype="multipart/form-data">
                        @csrf
                        <div class="row m-3">
                            <div class=" col-xl-6 col-sm-12">
                                <div class="m-2">
                                    <label for="add_code_id">Adhesive Name</label>
                                    <input type="text" name="code" id="add_code_id" placeholder="Input Code Chemical"
                                        class="form-control">
                                </div>
                                <div class="m-2">
                                    <title>Models</title>
                                    <label for="add_model_id">Model</label>
                                    <input type="text" name="model" list="list_model" id="add_model_id"
                                        placeholder="Input Model" class="form-control">
                                    <datalist id="list_model"></datalist>
                                </div>
                                <div class="m-2">
                                    <label for="add_supplier_id">Adhesive Supplier</label>
                                    <input type="text" name="supplier" list="list_suplier" id="add_supplier_id"
                                        placeholder="Input Supplier" class="form-control">
                                    <datalist id="list_suplier"></datalist>
                                </div>
                            </div>
                            <div class=" col-xl-6 col-sm-12">
                                <div class="m-2">
                                    <label for="add_component_id">Adhesives Kind</label>
                                    <select name="adhesive_kind" id="adhesive_kind_id" class="form-control">
                                        <option value="Water Base">Water Base</option>
                                        <option value="Solvent Base">Solvent Base</option>
                                    </select>
                                </div>
                                <div class="m-2">
                                    <label for="add_component_id">Component</label>
                                    <input type="text" name="component" list="list_component" id="add_component_id"
                                        placeholder="Input Component" class="form-control">
                                    <datalist id="list_component"></datalist>
                                </div>
                                <div class="m-2">
                                    <label for="add_type_id">Type of Adhesive</label>
                                    <input type="text" name="type" list="list_type" id="add_type_id"
                                        placeholder="Input Type" class="form-control">
                                    <datalist id="list_type"></datalist>
                                </div>
                            </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_update_database" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" style="max-width:95%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">UPDATE DATA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="model_update" id="model_update_id">
                    <div id="modal-body-update">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateDatabaseModal()">Update</button>
                    <button type="button" id="button_delete_modal" class="btn btn-danger"
                        onclick="deleteDatabaseModal()">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" style="max-width:60%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <table class="table table-striped table-hover table-bordered align-middle">
                        <thead>
                            <tr>
                                <th class="table-primary" scope="col">No</th>
                                <th class="table-primary" scope="col">Component</th>
                                <th class="table-primary" scope="col">Type</th>
                                <th class="table-primary" scope="col">Code Chemical</th>
                                <th class="table-primary" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id='tbody_data_delete'>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    @include('lab.script.script_database_cemical')
@endsection
