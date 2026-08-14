@extends('layouts.index')

@section('content')
<br>
<style>
    .rack{
        height: 300px;
        border: 3px solid black;
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <section class="col-lg-12 connectedSortable">
            <div class="card bg-dark">
                <div class="card-header">
                    <div class="text-center">
                        <p class="h4 mb-3 fw-bold">TOOLING DATA</p>
                        <footer class="blockquote-footer">PAD PRESS STOCKFIT</footer>
                    </div>
                </div>
                    <div class="row">
                        <button type="button" id="addBarcode" class="btn btn-primary col-xl-1 col-sm-6 m-3">Print Barcode</button>
                        <input type="text" name="search" id="search_data" onfocus="this.value=''" placeholder="Scan Hire" class="form-control col-xl-1 col-sm-6 m-3">
                        <input type="text" name="search_model" id="search_data_model" onfocus="this.value=''" placeholder="Search Model" class="form-control col-xl-1 col-sm-6 m-3">
                        <input type="hidden" name="link" id="link_" value="{{ url('/tooling/pad_press_stockfit/scan/') }}">
                    </div>
                    <div style="overflow-x:auto;" class="px-2 tableFixHead">
                        <table class="table text-center">
                            <thead class="bg-dark">
                                <tr>
                                    <th>No</th>
                                    <th>ID Barcode</th>
                                    <th>Pad Press Production</th>
                                    <th>Model</th>
                                    <th>Size</th>
                                    <th>Side</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Audit Terakhir</th>
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
<div class="modal fade" id="showRack" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">RACK LOCATION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="1A">1A</div>
                                <div class="col-6 rack" id="1B">1B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="2A">2A</div>
                                <div class="col-6 rack" id="2B">2B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="3A">3A</div>
                                <div class="col-6 rack" id="3B">3B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="4A">4A</div>
                                <div class="col-6 rack" id="4B">4B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="5A">5A</div>
                                <div class="col-6 rack" id="5B">5B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="6A">6A</div>
                                <div class="col-6 rack" id="6B">6B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="7A">7A</div>
                                <div class="col-6 rack" id="7B">7B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="8A">8A</div>
                                <div class="col-6 rack" id="8B">8B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="9A">9A</div>
                                <div class="col-6 rack" id="9B">9B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="10A">10A</div>
                                <div class="col-6 rack" id="10B">10B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="11A">11A</div>
                                <div class="col-6 rack" id="11B">11B</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-6 rack" id="12A">12A</div>
                                <div class="col-6 rack" id="12B">12B</div>
                            </div>
                        </div>
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
                    <input type="hidden" name="id" id="id_data">
                    <div style="overflow-x:auto;" class="px-2 tableFixHeadModal">
                        <table class="table table-striped text-center mt-2" id="table-scan">
                            <tr>
                                <th>ID Barcode</th>
                                <td id="id_barcode_scan"></td>
                            </tr>
                            <tr>
                                <th>Pad Press Production</th>
                                <td>
                                    <input type="date" name="pad_press_production" id="pad_press_production_id" class="form-control text-center option_update">
                                </td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>
                                    <select name="model" id="id_model_scan" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>
                                    <select name="gender" id="id_gender_scan" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Version</th>
                                <td>
                                    <select name="version" id="id_version_option" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Remark</th>
                                <td>
                                    <select name="remark" id="id_remark_option" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td>
                                    <select name="size" id="id_size_scan" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Side</th>
                                <td>
                                    <select name="side" id="id_side_scan" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>
                                    <select name="location" id="id_location_scan" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr id="tr_no_rack" style="display: none">
                                <th>No Rack</th>
                                <td>
                                    <select name="no_rack" id="no_rack_option" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status" id="id_status_scan" class="form-control text-center option_update">
                                    </select>
                                </td>
                            </tr>
                            <tr class="div_update" style="display: none">
                                <th>Reason</th>
                                <td>
                                    <select name="reason" id="reason_option" class="form-control text-center option_update">
                                        <option value="Moving">Moving</option>
                                        <option value="Audit">Audit</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div class="col-lg-4 col-sm-6 div_update" style="display: none">
                            <button type="submit" class="btn btn-success btn-block" id="update_id">Update</button>
                            <button type="button" class="btn btn-warning btn-block" id="cancle_id">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@include('tooling.padPress.scripts.script')
@endsection

