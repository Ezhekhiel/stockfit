<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap.min.css') }}">
    <style>
        @media screen and (max-width: 800px) {
            .table_history {
                font-size: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-right mt-2">
            @auth
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Welcome Back, <strong>{{ auth()->user()->name }}</strong>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <form action="/logout" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item" href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> LOGOUT</button>
                    </form>
                </div>
            @else
                <a href="/login" class="btn btn-light" type="submit"><i class="bi bi-box-arrow-in-right"></i> LOGIN</a>
            @endauth
        </div>
        <h1 class="text-center mb-4 mt-4 bg-light">PAD PRESS STATUS</h1>
        <div class="col-sm-6 col-xl-1">
            <li class="nav-item ml-3" id="nav-loading" style="display: none">
                <div class="lds-dual-ring text-light"></div>
            </li>
        </div>
        <nav>
            <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button>
              <button class="nav-link" id="nav-history-tab" data-toggle="tab" data-target="#nav-history" type="button" role="tab" aria-controls="nav-history" aria-selected="false">History</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div id="pageMessages" class="mt-2"></div>
                    <form method="post" action="#" id="form-update" name="formInput" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id_data">
                        <div style="overflow-x:auto;" class="px-2 tableFixHead">
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
                                        <select name="model" id="id_model_scan" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>
                                        <select name="gender" id="id_gender_scan" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Version</th>
                                    <td>
                                        <select name="version" id="id_version_option" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Remark</th>
                                    <td>
                                        <select name="remark" id="id_remark_option" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Size</th>
                                    <td>
                                        <select name="size" id="id_size_scan" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Side</th>
                                    <td>
                                        <select name="side" id="id_side_scan" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>
                                        <select name="location" id="id_location_scan" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr id="tr_no_rack" style="display: none">
                                    <th>No Rack</th>
                                    <td>
                                        <select name="no_rack" id="no_rack_option" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <select name="status" id="id_status_scan" class="form-control text-center option_update" disabled="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr class="div_update" style="display: none">
                                    <th>Reason</th>
                                    <td>
                                        <select name="reason" id="reason_option" class="form-control text-center option_update" disabled="true">
                                            <option value="Moving">Moving</option>
                                            <option value="Audit">Audit</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            @auth
                                <div class="col-lg-4 col-sm-6 div_update" style="display: none">
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="submit" class="btn bt n-success btn-block" id="update_id">Update</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-warning btn-block" id="cancle_id">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </form>
            </div>
            <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab">
                <div style="overflow-x:auto;" class="px-2 tableFixHead table_history">
                    <table class="table table-striped text-center mt-2">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Updated Time</th>
                            </tr>
                        </thead>
                        <tbody id="table-history"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
@include('tooling.padPress.scripts.scan')
<script src="{{ asset("plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<script src="{{ asset("dist/js/popper.min.js") }}"></script>
<script src="{{ asset("plugins/bootstrap/js/bootstrap.min.js") }}"></script>
</html>


