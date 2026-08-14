@extends('layouts.main')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="text-center">
                            <p class="h4 mb-3 fw-bold mt-3">DAILY BALANCE STOCKFIT LINE (P-Card System)</p>
                            {{-- <footer class="blockquote-footer">Buymonth : {{ date('m') }}-{{ date('m',strtotime('+2 month')) }}'{{ date('y') }}</footer> --}}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="col-12 justify-content-center">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade" id="reject" role="tabpanel" aria-labelledby="reject-tab">
                                    <div class="col-12 card">
                                        <div class="card-header">
                                            <h5 class="fw-bold">Reject Stockfit</h5>
                                            <ul class="nav nav-tabs" id="myTabReject" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link" id="reject-admin-tab" data-bs-toggle="tab"
                                                        href="#by_size_run_reject" role="tab"
                                                        aria-controls="reject_admin" aria-selected="false">By Size Run</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-content" id="TabContentReject">
                                            <div class="tab-pane fade" id="by_size_run_reject" role="tabpanel"
                                                aria-labelledby="reject-admin-tab">
                                                <div class="card-body">
                                                    <form method="POST" action="#" id="form_reject"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row justify-content-center" style="font-size: 80%">
                                                            <div class="col-12 border p-3">
                                                                <div class="d-grid">
                                                                    <button type="submit"
                                                                        class="btn btn-secondary btn-lg">SAVE</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-3 border p-3 m-2">
                                                                <div class="mb-2">
                                                                    <label for="id_shift_reject"
                                                                        class="form-label">Shift</label>
                                                                    <select name="shift" id="id_shift_reject"
                                                                        class="form-select form-select-sm">
                                                                        <option>Pilih Shift</option>
                                                                        <option value="A">A</option>
                                                                        <option value="B">B</option>
                                                                        <option value="NON SHIFT">NON SHIFT</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_pengawas_input"
                                                                        class="form-label">Pengawas</label>
                                                                    <select name="pengawas" id="id_pengawas_output"
                                                                        class="form-select form-select-sm select-pengawas"></select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label for="id_jam_reject" class="form-label">Jam Ke
                                                                        -</label>
                                                                    <select name="jam" id="id_jam_reject"
                                                                        class="form-select form-select-sm">
                                                                        <option value="">Pilih Jam Kerja</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-8 border p-3 m-2">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-2">
                                                                            <label for="id_defect_reject"
                                                                                class="form-label">Defect</label>
                                                                            <select name="jenis" id="id_defect_reject"
                                                                                class="form-select form-select-sm">
                                                                                <option value="Open Bonding">Open Bonding
                                                                                </option>
                                                                                <option value="Over Cement">Over Cement
                                                                                </option>
                                                                                <option value="Over Primer">Over Primer
                                                                                </option>
                                                                                <option value="Solelaying">Solelaying
                                                                                </option>
                                                                                <option value="Dirty">Dirty</option>
                                                                                <option value="Different Color">Different
                                                                                    Color</option>
                                                                                <option value="Top Gauge">Top Gauge
                                                                                </option>
                                                                                <option value="Off Center">Off Center
                                                                                </option>
                                                                                <option value="Attaching">Attaching
                                                                                </option>
                                                                                <option value="Damage Material">Damage
                                                                                    Material</option>
                                                                                <option value="Painting">Painting</option>
                                                                                <option value="Other/Trimming">
                                                                                    Other/Trimming</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-2">
                                                                            <label for="id_date_reject"
                                                                                class="form-label">Date</label>
                                                                            <input type="date"
                                                                                class="form-control form-control-sm"
                                                                                name="date" id="id_date_reject"
                                                                                aria-describedby="dateID">
                                                                        </div>
                                                                        <div class="mb-2">
                                                                            <label for="id_qty_reject"
                                                                                class="form-label">QTY</label>
                                                                            <input type="number"
                                                                                class="form-control form-control-sm reset"
                                                                                name="qty" id="id_qty_reject"
                                                                                aria-describedby="qtyID">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-xl-1">
                                <input type="date" name="date" id="date_search" value="{{ date('Y-m-d') }}"
                                    class="form-control m-3">
                            </div>
                            <div class="col-sm-4 col-xl-1">
                                <select class="form-select m-3" name="line" id="line_search">
                                    @foreach ($arrLine as $row => $a)
                                        @php
                                            $explode = explode('-', $a);
                                        @endphp
                                        <option value="{{ $row }}">{{ $explode[0] . '-Line-' . $explode[1] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 col-xl-1">
                                <button type="button" onclick="refreshMain()" class="btn btn-info m-3">Refresh</button>
                            </div>
                            <div class="col-sm-6 col-xl-1" style="display:none">
                                <select class="form-select m-3" name="shift" id="shift_search">
                                    <option value="SHIFT A">SHIFT A</option>
                                    <option value="SHIFT B">SHIFT B</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-xl-1">
                                <li class="nav-item ms-3" id="nav-loading" style="display: none">
                                    <div class="lds-dual-ring text-light"></div>
                                </li>
                            </div>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="table table-hover text-center" id="display_perLine" style="font-size: 100%">
                                <thead>
                                    <tr>
                                        <th class="align-middle">Jam Kerja</th>
                                        <th class="align-middle bg-success text-white">Input</th>
                                        <th class="align-middle bg-primary text-white">Output</th>
                                        {{-- <th class="align-middle bg-danger text-white">Reject</th>
                                    <th class="align-middle bg-danger text-white">RFT</th> --}}
                                        <th class="align-middle bg-secondary text-white" onclick="openDetailPerDay()">BTS
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tb_perCell"></tbody>
                            </table>
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

    {{-- Modal Detail Perline --}}
    <div class="modal fade" id="modal-show-data-detail-perline" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Data Detail PerLine </h5>
                    <p class="fw-bold mb-0 ms-2" id="title-modal-detail-perLine"></p>
                    <li class="nav-item ms-3" id="nav-loading2" style="display: none">
                        <div class="lds-dual-ring text-light"></div>
                    </li>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="#" id="delete-modal-detailperline" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="form" id="form-modal-detail-perline">
                        <input type="hidden" name="jam" id="jam-modal-input-perLine">
                        <input type="hidden" name="date" id="date-modal-input-perLine">
                        <input type="hidden" name="pengawas" id="pengawas-modal-input-perLine">
                        <input type="hidden" name="shift" id="shift-modal-input-perLine">
                        <div class="row mb-2">
                            <div class="col-6 card align-middle p-2">
                                <p class="mb-1">Date: </p>
                                <h4 class="fw-bold text-center" id="date-modal-detail-perline"></h4>
                            </div>
                            <div class="col-6 card align-middle p-2">
                                <p class="mb-1">Line: </p>
                                <h4 class="fw-bold text-center" id="line-modal-detail-perline"></h4>
                            </div>
                        </div>
                        <div style="overflow-x:auto;" class="tableFixHead">
                            <table class="table table-bordered border-dark table-hover text-center"
                                style="font-size: 80%;">
                                <thead class="table-secondary">
                                    <tr>
                                        <th rowspan="2" class="align-middle">Line</th>
                                        <th rowspan="2" class="align-middle">QTY Order</th>
                                        <th colspan="4" class="bg-success text-white">Input</th>
                                        <th colspan="4" class="bg-primary text-white">Output</th>
                                        <th rowspan="2" class="align-middle">MIX</th>
                                        <th rowspan="2" class="align-middle">Total MIX</th>
                                        <th rowspan="2" class="align-middle">MIX %</th>
                                        <th rowspan="2" class="align-middle">Volume %</th>
                                        <th rowspan="2" class="align-middle">BTS %</th>
                                    </tr>
                                    <tr>
                                        <th>PO</th>
                                        <th>Size</th>
                                        <th>QTY Input</th>
                                        <th>Total Prs (Volume)</th>
                                        <th>PO</th>
                                        <th>Size</th>
                                        <th>QTY Output</th>
                                        <th>Total Prs (Volume)</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-detail-perline">
                                </tbody>
                            </table>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @auth
                        @if (auth()->user()->role_id == 7 ||
                                auth()->user()->role_id == 1 ||
                                auth()->user()->role_id == 5 ||
                                auth()->user()->role_id == 8)
                            <button type="submit" class="btn btn-danger">Delete</button>
                        @endif
                    @endauth
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tracking PO --}}
    <div class="modal fade" id="modalTrackingPO" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tracking PO - </h5>
                    <p class="fw-bold mb-0 ms-2" id="poTrackingTitle"></p>
                    <li class="nav-item ms-3" id="nav-loading2" style="display: none">
                        <div class="lds-dual-ring text-light"></div>
                    </li>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center card-header">INPUT</div>
                            <div style="overflow-x:auto;" class="tableFixHead">
                                <table class="table table-hover text-center" style="font-size: 80%;">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>LINE</th>
                                            <th>TIME</th>
                                            <th>SIZE</th>
                                            <th>QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tracingIncomingPO">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center card-header">OUTPUT</div>
                            <div style="overflow-x:auto;" class="tableFixHead">
                                <table class="table table-hover text-center" style="font-size: 80%;">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>LINE</th>
                                            <th>TIME</th>
                                            <th>SIZE</th>
                                            <th>QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tracingOutputPO">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @include('stockfit.stockfit_line.script.script_output_stockfit_erp')
@endsection
