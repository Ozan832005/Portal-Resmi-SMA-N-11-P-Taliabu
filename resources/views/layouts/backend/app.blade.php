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
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    @include('layouts.backend.style')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    @include('layouts.backend.header')
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    @include('layouts.backend.menu')
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        @yield('content')
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0">
            <span>&copy; {{ date('Y') }} SMA Negeri 11 Pulau Taliabu</span>
        </p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button" aria-label="Kembali ke atas" title="Kembali ke atas">
        <i data-feather="arrow-up"></i>
    </button>
    <!-- END: Footer-->

    <div
        class="km-delete-dialog"
        id="km-delete-dialog"
        aria-hidden="true"
        hidden
    >
        <div class="km-delete-dialog__backdrop"></div>
        <section
            class="km-delete-dialog__panel"
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="km-delete-dialog-title"
            aria-describedby="km-delete-dialog-message"
            tabindex="-1"
        >
            <span class="km-delete-dialog__icon" aria-hidden="true">
                <i data-feather="trash-2"></i>
            </span>
            <h2 id="km-delete-dialog-title">Hapus data ini?</h2>
            <p id="km-delete-dialog-message">Data yang dihapus tidak dapat dikembalikan.</p>
            <div class="km-delete-dialog__actions">
                <button class="km-delete-dialog__cancel" type="button" data-delete-cancel>
                    Batal
                </button>
                <button class="km-delete-dialog__confirm" type="button" data-delete-confirm>
                    Ya, hapus
                </button>
            </div>
        </section>
    </div>

    @include('layouts.backend.scripts')
    @yield('scripts')
</body>
<!-- END: Body-->

</html>