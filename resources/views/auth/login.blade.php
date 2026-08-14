<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('dist/img/icon.ico') }}" type="image/ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PT. Parkland World Indonesia 2</title>
    <link href="{{ asset('assets/css/login_rev') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>

<body>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <img src="{{ asset('assets/img/login.jpg') }}" class="img-fluid" alt="Responsive image">
                    </div>
                    <div class="col-md-7" style="background-color: #f4f6f9;">
                        <div class="card-body">
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div><br>
                            @endif
                            @if (session()->has('login_error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('login_error') }}
                                </div><br>
                            @endif
                            <div class="brand-wrapper">
                                <img src="{{ asset('assets/img/logo.svg') }}" class="img-fluid" alt="Responsive image">
                            </div>
                            <p class="login-card-description" style="font-family: Century Gothic; color: #235275;">Sign
                                into your account</p>
                            <form method="post" action="/login">
                                @csrf
                                <input type="hidden" name="link" value="{{ $previous_link }}">
                                <div class="form-group">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Email address" value="{{ old('email') }}"autofocus required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="***********" required>
                                </div>
                                <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit"
                                    value="Login">
                            </form>
                            <a href="/forgot_password" class="forgot-password-link">Forgot password?</a>
                            <nav class="login-card-footer-nav">
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</body>

</html>
