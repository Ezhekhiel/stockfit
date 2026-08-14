@extends('layouts.main')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold text-dark">
                        Form Register User
                    </h3>
                    <small class="text-muted">
                        Tambahkan user baru ke dalam sistem
                    </small>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Register
                        </li>
                    </ol>
                </div>
            </div>

            {{-- Form --}}
            <div class="row justify-content-center">
                <div class="col-lg-7">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0 pt-4 pb-0">
                            <h4 class="fw-semibold mb-1">
                                Register Account
                            </h4>

                            <p class="text-muted small mb-0">
                                Isi data user dengan lengkap
                            </p>
                        </div>

                        <div class="card-body p-4">

                            {{-- Alert --}}
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show rounded-3">
                                    {{ session('success') }}

                                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                                    </button>
                                </div>
                            @endif

                            <form method="POST" action="/register">

                                @csrf

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Full Name
                                    </label>

                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        placeholder="Input full name">

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- NIK --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        NIK
                                    </label>

                                    <input type="text" name="nik" value="{{ old('nik') }}"
                                        class="form-control form-control-lg @error('nik') is-invalid @enderror"
                                        placeholder="Input NIK">

                                    @error('nik')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Email Address
                                    </label>

                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="example@email.com">

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Password
                                    </label>

                                    <input type="password" name="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="Input password">

                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Confirm Password
                                    </label>

                                    <input type="password" name="password_confirmation" class="form-control form-control-lg"
                                        placeholder="Repeat password">
                                </div>

                                {{-- Role --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Jabatan
                                    </label>

                                    <select name="role_id"
                                        class="form-select form-select-lg @error('role_id') is-invalid @enderror">

                                        <option value="">-- Pilih Jabatan --</option>

                                        <option value="1">MASTER</option>
                                        <option value="2">Manager</option>
                                        <option value="3">Assistant</option>
                                        <option value="4">Chief</option>
                                        <option value="5">Staff</option>
                                        <option value="6">Admin Distribution Center</option>
                                        <option value="7">Admin Stockfit</option>
                                        <option value="8">Outsiders</option>
                                        <option value="9">Manager Engineering</option>
                                        <option value="10">Pengawas Engineering</option>
                                        <option value="13">Admin Produksi</option>
                                        <option value="14">Admin Engineering</option>
                                        <option value="15">Mekanik</option>
                                        <option value="16">Courier</option>
                                    </select>

                                    @error('role_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Button --}}
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-semibold">

                                        <i class="bi bi-person-plus-fill me-1"></i>
                                        Register User
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!--end::App Content Header-->
@endsection
