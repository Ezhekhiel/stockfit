@extends('layouts.main')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
        }

        .select2-container .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px !important;
            font-size: 14px !important;
        }

        .select2-container .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container py-4">

            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-hdd-network fs-1 text-primary me-3"></i>

                <div>
                    <h2 class="mb-0">
                        Manage Chat Laporan WhatsApp
                    </h2>
                    <small class="text-muted">
                        di Group
                    </small>
                </div>
            </div>

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs" id="stitchingTab" role="tablist">

                <!-- Process -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule"
                        type="button" role="tab" onclick="main_schedule()">

                        <i class="bi bi-calendar-check me-2"></i>
                        Pendaftaran Jadwal
                    </button>
                </li>

            </ul>

            <!-- Tab Content -->
            <div class="tab-content border border-top-0 bg-white p-4 shadow-sm rounded-bottom" id="stitchingTabContent">

                <!-- Process Content -->
                <div class="tab-pane fade show active" id="schedule" role="tabpanel">

                    <h4 class="mb-3 text-warning">
                        <i class="bi bi-calendar-check"></i>
                        Jadwal
                    </h4>

                    <p class="text-muted">
                        Pendaftaran jadwal pengiriman informasi di whatsapp
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <div class="col-4">
                            <input type="text" class="form-control" id="search_schedule" onkeyup="main_schedule()"
                                placeholder="Cari Jadwal">
                        </div>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            onclick="open_modal_tambah_jadwal()" data-bs-target="#scheduleModal">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Jadwal
                        </button>

                    </div>
                    <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">

                        <table class="table table-hover align-middle text-center">

                            <thead class="table-light">

                                <tr>
                                    <th>No</th>
                                    <th>Nama Group</th>
                                    <th>Nama Jadwal</th>
                                    <th>Time</th>
                                    <th>Next Run</th>
                                    <th>Last Run</th>
                                    <th>Status</th>
                                    <th class="bg-secondary text-light" style="width: 10%">Action</th>
                                </tr>

                            </thead>

                            <tbody id="tbody_schedule">
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <div id="alertMessage" class="alert alert-dismissible fade show" style="display: none;">

            <span id="alertText"></span>

            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>

        </div>
        <div class="modal fade" id="scheduleModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <form id="formSchedule">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-calendar-plus"></i>
                                Tambah Jadwal
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Group WhatsApp <span class="text-danger">*</span>
                                    </label>

                                    <select name="group_id" class="select2" id="list_group" required>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nama Schedule <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="name" id="id_name" class="form-control"
                                        placeholder="Contoh: Daily Production Report" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Send Time
                                    </label>

                                    <input type="time" class="form-control" name="send_time">

                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Status
                                    </label>
                                    <select name="status" id="status_id" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Project Name
                                    </label>
                                    <input type="text" name="project" id="id_project" class="form-control" required>
                                </div>


                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i>
                                Batal
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                Simpan Jadwal
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>
        @include('scripts.wa.manage')
    @endsection
