<!DOCTYPE html>
<html class="loading semi-dark-layout" lang="en" data-layout="semi-dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Sekolahku adalah aplikasi manajemen sekolah berbasis website yang di bangun dan di kembangkan dengan Framework Laravel">
    <meta name="keywords" content="">
    <meta name="author" content="Andri Desmana">
    <title>Daftar | SMA Negeri 11 Pulau Taliabu</title>
    <link rel="icon" type="image/png" href="{{ $schoolLogo ? asset('storage/images/logo/' . $schoolLogo) : asset('Assets/Frontend/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Assets/Backend/css/pages/page-auth.css')}}">
    <!-- END: Page CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-v2">
                    <div class="auth-inner row m-0">
                        <!-- Brand logo--><a class="brand-logo" href="/">
                            <img src="{{ $schoolLogo ? asset('storage/images/logo/' . $schoolLogo) : asset('Assets/Frontend/img/favicon.png') }}" alt="{{ __('Logo SMA Negeri 11 Pulau Taliabu') }}" height="28">
                            <h2 class="brand-text text-primary ml-1">SMA Negeri 11 Pulau Taliabu</h2>
                        </a>
                        <!-- /Brand logo-->
                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="{{asset('Assets/Backend/images/illustration/login-v2.svg')}}" alt="Login V2" /></div>
                        </div>
                        <!-- /Left Text-->
                        <!-- Login-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5 py-5 py-lg-0">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                @if($message = Session::get('error'))
                                    <div class="alert alert-danger" role="alert">
                                        <div class="alert-body">
                                            <strong>{{ $message }}</strong>
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                        </div>
                                    </div>
                                @endif
                                <h2 class="card-title font-weight-bold mb-1">Selamat datang 👋</h2>
                                <p class="card-text mb-2">Pendaftaran PPDB SMA Negeri 11 Pulau Taliabu</p>
                                <form class="auth-login-form mt-2" action="{{route('register.store')}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label" for="register-name">Nama Lengkap</label>
                                        <input id="register-name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" placeholder="Masukan Nama Lengkap" autocomplete="name" autofocus />
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="register-email">Email</label>
                                        <input id="register-email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="Masukan Email" autocomplete="email" />
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="register-whatsapp">No WhatsApp Calon Murid</label>
                                        <input id="register-whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Masukan No WhatsApp" autocomplete="tel" />
                                        @error('whatsapp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="register-school">Asal Sekolah</label>
                                        <input id="register-school" class="form-control @error('asal_sekolah') is-invalid @enderror" type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" placeholder="Masukan Asal Sekolah" autocomplete="organization" />
                                        @error('asal_sekolah')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="register-password">Password Login</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input id="register-password" class="form-control form-control-merge @error('password') is-invalid @enderror" type="password" name="password" placeholder="············" autocomplete="new-password" />
                                            <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="register-confirm-password">Konfirmasi Password</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input id="register-confirm-password" class="form-control form-control-merge @error('confirm_password') is-invalid @enderror" type="password" name="confirm_password" placeholder="············" autocomplete="new-password" />
                                            <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                                            @error('confirm_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" id="remember-me" type="checkbox" />
                                            <label class="custom-control-label" for="remember-me"> Remember Me</label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Daftar</button>
                                </form>
                                </div>
                        </div>
                        <!-- /Login-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('Assets/Backend/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('Assets/Backend/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('Assets/Backend/js/core/app-menu.js')}}"></script>
    <script src="{{asset('Assets/Backend/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset('Assets/Backend/js/scripts/pages/page-auth-login.js')}}"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
