<!doctype html>
<html class="no-js" lang="{{ app()->getLocale() }}">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ !empty($footer->logo) ? asset('storage/images/logo/' . $footer->logo) : asset('Assets/Frontend/img/favicon.png') }}">
    <script>
        (function () {
            var theme = 'light';

            try {
                var savedTheme = localStorage.getItem('km-theme');

                if (savedTheme === 'dark' || savedTheme === 'light') {
                    theme = savedTheme;
                }
            } catch (error) {
                console.warn('Preferensi tema tidak dapat dibaca dari penyimpanan browser.', error);
            }

            document.documentElement.dataset.theme = theme;
        })();
    </script>
    @include('layouts.Frontend.style')
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader" role="status" aria-label="{{ __('Memuat SMA Negeri 11 Pulau Taliabu') }}">
        <span class="km-preloader-mark">
            @if (!empty($footer->logo))
                <img
                    class="km-preloader-logo"
                    src="{{ asset('storage/images/logo/' . $footer->logo) }}"
                    alt="Logo SMA Negeri 11 Pulau Taliabu">
            @else
                <i class="fa fa-university km-preloader-icon" aria-hidden="true"></i>
            @endif
        </span>
        <span class="km-preloader-name">SMA Negeri 11 Pulau Taliabu</span>
    </div>
    <!-- Preloader End Here -->
    <!-- Main Body Area Start Here -->
    <div id="wrapper">
        @yield('content')
        <!-- Header Area Start Here -->
        <header>
           @include('frontend.content.header')
        </header>
        <!-- Header Area End Here -->

        <!-- Slider 1 Area Start Here -->
        <div class="slider1-area overlay-default">
            @yield('slider')
        </div>
        <!-- Slider 1 Area End Here -->

        <!-- About 1 Area Start Here -->
            @yield('about')
        <!-- About 1 Area End Here -->

        <!-- Statistik & fasilitas Start Here -->
            @yield('facts')
        <!-- Statistik & fasilitas End Here -->

        <!-- Video Area Start Here -->
            @yield('video')
        <!-- Video Area End Here -->

        <!-- Lecturers Area Start Here -->
            @yield('guru')
        <!-- Lecturers Area End Here -->

        <!-- News and Event Area Start Here -->
            @yield('beritaEvent')
        <!-- News and Event Area End Here -->
        
        <!-- Footer Area Start Here -->
        <footer>
            @include('frontend.content.footer')
        </footer>
        <!-- Footer Area End Here -->
    </div>
    <!-- Main Body Area End Here -->
    <div class="km-login-modal" id="km-login-modal" data-login-modal hidden>
        <div class="km-login-modal__backdrop" data-login-modal-close></div>
        <section class="km-login-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="km-login-modal-title">
            <header class="km-login-modal__header">
                <h2 id="km-login-modal-title">{{ __('Masuk ke portal sekolah') }}</h2>
                <button type="button" class="km-login-modal__close" data-login-modal-close aria-label="{{ __('Tutup') }}">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </header>
            <div class="km-login-modal__content">
                <span class="km-login-modal__loading" data-login-modal-loading data-submitting-text="{{ __('Sedang memverifikasi akun...') }}" role="status">{{ __('Menyiapkan form masuk...') }}</span>
                <iframe class="km-login-modal__frame" title="{{ __('Form masuk SMA Negeri 11 Pulau Taliabu') }}" loading="eager"></iframe>
            </div>
        </section>
    </div>
    <script src="{{ asset('Assets/Frontend/js/km-login-modal.js') }}?v={{ filemtime(public_path('Assets/Frontend/js/km-login-modal.js')) }}"></script>
    @include('layouts.Frontend.scripts')
</body>

</html>