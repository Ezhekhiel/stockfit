@extends('layouts.main')

@section('content')
    <br>
    <style>
        .rack {
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
                            <p class="h4 mb-3 fw-bold">DATA CHEMICAL</p>
                            <footer class="blockquote-footer">Mixing Glue System</footer>
                        </div>
                    </div>
                    <div class="card-body">
                        <li class="nav-item ml-3" id="nav-loading" style="display: none">
                            <div class="lds-dual-ring text-light"></div>
                        </li>
                        <div style="overflow-x:auto;">
                            <table class="table text-center mt-2 text-center" id="table-scan">
                                @if (!$data)
                                    <tr>
                                        <th class="text-center">TEKO KOSONG</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th>ID Barcode</th>
                                        <td>{{ $data->id_barcode }}</td>
                                    </tr>
                                    <tr>
                                        <th>Model</th>
                                        <td>{{ $data->model }}</td>
                                    </tr>
                                    <tr>
                                        <th>Supplier</th>
                                        <td>{{ $data->supplier }}</td>
                                    </tr>
                                    <tr>
                                        <th>Component</th>
                                        <td>{{ $data->component }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td>{{ $data->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Code Chemical</th>
                                        <td>{{ $data->code_chemical }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gram</th>
                                        <td>{{ $data->gram }}g</td>
                                    </tr>
                                    <tr>
                                        <th>Line</th>
                                        <td>{{ $data->line }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Mixing</th>
                                        <td>{{ $data->time_mixing }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expires On</th>
                                        <td>{{ $expire_on }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div id="pageMessages"></div>
    @include('lab.script.script_lab')
@endsection
