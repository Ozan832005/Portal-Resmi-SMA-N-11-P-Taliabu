<div id="header2" class="header4-area">
    <div class="header-top-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="header-top-left">
                        <div class="logo-area">
                            <a href="/" class="header-brand" aria-label="{{ __('Beranda SMA Negeri 11 Pulau Taliabu') }}">
                                @if (@$footer->logo == NULL)
                                    <img class="img-responsive" src="{{asset('Assets/Frontend/img/favicon.png')}}" alt="{{ __('Logo sekolah') }}">
                                @else
                                    <img class="img-responsive" src="{{asset('storage/images/logo/' .$footer->logo)}}" alt="Logo SMA Negeri 11 Pulau Taliabu">
                                @endif
                                <span class="school-name">SMA 11 PULAU TALIABU</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="header-top-right">
                        <div class="header-action-group">
                            @auth
                                <a href="/home" class="header-btn header-btn--secondary">{{ __('Dashboard') }}</a>
                            @else
                                    <a href="{{ route('login') }}" class="header-btn header-btn--secondary" data-login-modal-open aria-controls="km-login-modal"><i class="fa fa-sign-in" aria-hidden="true"></i>{{ __('Masuk') }}</a>
                                    <a href="{{ route('profile.sekolah') }}" class="apply-now-btn2"><i class="fa fa-compass" aria-hidden="true"></i>{{ __('Explore') }}</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-menu-area bg-primary" id="sticker">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <nav id="desktop-nav">
                        <ul>
                            <li class="{{ (request()->is('/')) ? 'active' : '' }}"><a href="/"><span class="km-nav-tile"><i class="fa fa-home" aria-hidden="true"></i></span>{{ __('Beranda') }}</a></li>
                            <li><a href="#"><span class="km-nav-tile"><i class="fa fa-university" aria-hidden="true"></i></span>{{ __('Tentang Kami') }}</a>
                                <ul>
                                    <li><a href="{{route('profile.sekolah')}}"><i class="fa fa-building-o" aria-hidden="true"></i>{{ __('Profil Sekolah') }}</a></li>
                                    <li><a href="{{route('visimisi.sekolah')}}"><i class="fa fa-compass" aria-hidden="true"></i>{{ __('Visi dan Misi') }}</a></li>
                                </ul>
                            </li>

                            <li><a href="#"><span class="km-nav-tile"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>{{ __('Program') }}</a>
                                <ul>
                                    <li><a href="{{ route('kurikulum') }}"><i class="fa fa-list-alt" aria-hidden="true"></i>{{ __('Kurikulum') }}</a></li>
                                    <li class="has-child-menu"><a href="#"><i class="fa fa-tasks" aria-hidden="true"></i>{{ __('Kegiatan') }}</a>
                                        <ul class="thired-level">
                                            @foreach ($kegiatanM as $kegiatans)
                                                <li><a href="{{url('kegiatan', $kegiatans->slug)}}">{{$kegiatans->nama}}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ (request()->is('berita') || request()->is('berita/*')) ? 'active' : '' }}"><a href="{{route('berita')}}"><span class="km-nav-tile"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>{{ __('Berita') }}</a></li>
                            <li class="{{ (request()->is('event') || request()->is('event/*')) ? 'active' : '' }}"><a href="{{route('event')}}"><span class="km-nav-tile"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>{{ __('Acara') }}</a></li>
                            <li><a href="{{url('ppdb')}}" target="_blank" rel="noopener"><span class="km-nav-tile"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>PPDB</a></li>

                            <li><a href="#"><span class="km-nav-tile"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></span>{{ __('Lainnya') }}</a>
                                <ul>
                                    <li><a href="{{ route('katalog.perpustakaan') }}"><i class="fa fa-book" aria-hidden="true"></i>{{ __('Perpustakaan') }}</a></li>
                                    <li><a href="{{ route('alumni') }}"><i class="fa fa-users" aria-hidden="true"></i>{{ __('Alumni') }}</a></li>
                                </ul>
                            </li>
                            <li class="km-theme-item">
                                <button type="button" class="km-theme-toggle" data-theme-toggle data-label-dark="{{ __('Aktifkan mode gelap') }}" data-label-light="{{ __('Aktifkan mode terang') }}" aria-label="{{ __('Aktifkan mode gelap') }}" aria-pressed="false">
                                    <span class="km-theme-toggle__track" aria-hidden="true">
                                        <i class="fa fa-sun-o"></i>
                                        <i class="fa fa-moon-o"></i>
                                        <span class="km-theme-toggle__thumb"></span>
                                    </span>
                                    <span class="sr-only">{{ __('Ganti tema') }}</span>
                                </button>
                            </li>
                            <li class="km-language-item">
                                <form action="{{ route('language.switch', app()->getLocale() === 'id' ? 'en' : 'id') }}" method="POST" aria-label="{{ __('Pilih bahasa') }}">
                                    @csrf
                                    <button type="submit" class="km-language-toggle" data-language="{{ app()->getLocale() === 'id' ? 'id' : 'en' }}" aria-label="{{ app()->getLocale() === 'id' ? 'Switch to English' : 'Ganti ke Bahasa Indonesia' }}">
                                        <span class="km-language-toggle__option">{{ __('ID') }}</span>
                                        <span class="km-language-toggle__option">{{ __('EN') }}</span>
                                        <span class="km-language-toggle__indicator" aria-hidden="true"></span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu Area Start -->
<div class="mobile-menu-area">
    <div class="mobile-header">
        <a href="/" class="mobile-brand" aria-label="{{ __('Beranda SMA Negeri 11 Pulau Taliabu') }}">
            @if (@$footer->logo == NULL)
                <img src="{{ asset('Assets/Frontend/img/favicon.png') }}" alt="{{ __('Logo sekolah') }}">
            @else
                <img src="{{ asset('storage/images/logo/' . $footer->logo) }}" alt="Logo SMA Negeri 11 Pulau Taliabu">
            @endif
            <span>SMA 11 <strong>PULAU TALIABU</strong></span>
        </a>

        <div class="mobile-header-actions">
            <button type="button" class="km-theme-toggle km-theme-toggle--mobile" data-theme-toggle data-label-dark="{{ __('Aktifkan mode gelap') }}" data-label-light="{{ __('Aktifkan mode terang') }}" aria-label="{{ __('Aktifkan mode gelap') }}" aria-pressed="false">
                <span class="km-theme-toggle__track" aria-hidden="true">
                    <i class="fa fa-sun-o"></i>
                    <i class="fa fa-moon-o"></i>
                    <span class="km-theme-toggle__thumb"></span>
                </span>
                <span class="sr-only">{{ __('Ganti tema') }}</span>
            </button>

            <details class="mobile-navigation">
                <summary class="mobile-nav-toggle" aria-label="{{ __('Menu navigasi') }}">
                    <span>{{ __('Menu') }}</span>
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </summary>
                <nav class="mobile-nav-panel" aria-label="{{ __('Navigasi utama') }}">
                    <div class="mobile-nav-head">
                        <a href="/" class="mobile-nav-brand" aria-label="{{ __('Beranda SMA Negeri 11 Pulau Taliabu') }}">
                            @if (@$footer->logo == NULL)
                                <img src="{{ asset('Assets/Frontend/img/favicon.png') }}" alt="{{ __('Logo sekolah') }}">
                            @else
                                <img src="{{ asset('storage/images/logo/' . $footer->logo) }}" alt="Logo SMA Negeri 11 Pulau Taliabu">
                            @endif
                            <span class="mobile-nav-brand-text">
                                <span>SMA NEGERI 11</span>
                                <strong>PULAU TALIABU</strong>
                            </span>
                        </a>

                        <button type="button" class="mobile-nav-close" aria-label="{{ __('Tutup menu') }}">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                    <ul>
                        <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}"><span class="km-nav-tile km-nav-tile--mobile"><i class="fa fa-home" aria-hidden="true"></i></span>{{ __('Beranda') }}</a></li>
                        <li class="mobile-nav-heading">{{ __('Tentang sekolah') }}</li>
                        <li class="mobile-nav-subitem"><a href="{{ route('profile.sekolah') }}">{{ __('Profil Sekolah') }}</a></li>
                        <li class="mobile-nav-subitem"><a href="{{ route('visimisi.sekolah') }}">{{ __('Visi dan Misi') }}</a></li>
                        <li class="mobile-nav-heading">{{ __('Program') }}</li>
                        <li class="mobile-nav-subitem"><a href="{{ route('kurikulum') }}">{{ __('Kurikulum') }}</a></li>
                        @if ($kegiatanM->isNotEmpty())
                            <li class="mobile-nav-heading">{{ __('Kegiatan') }}</li>
                            @foreach ($kegiatanM as $kegiatans)
                                <li class="mobile-nav-subitem"><a href="{{ url('kegiatan', $kegiatans->slug) }}">{{ $kegiatans->nama }}</a></li>
                            @endforeach
                        @endif
                        <li><a href="{{ route('berita') }}" class="{{ request()->is('berita*') ? 'active' : '' }}"><span class="km-nav-tile km-nav-tile--mobile"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>{{ __('Berita') }}</a></li>
                        <li><a href="{{ route('event') }}" class="{{ request()->is('event*') ? 'active' : '' }}"><span class="km-nav-tile km-nav-tile--mobile"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>{{ __('Acara') }}</a></li>
                        <li><a href="{{ route('publikasi') }}" class="{{ request()->is('publikasi*') ? 'active' : '' }}"><span class="km-nav-tile km-nav-tile--mobile"><i class="fa fa-video-camera" aria-hidden="true"></i></span>{{ __('Publikasi') }}</a></li>
                        <li><a href="{{ url('ppdb') }}" target="_blank" rel="noopener"><span class="km-nav-tile km-nav-tile--mobile"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>PPDB</a></li>
                        <li class="mobile-nav-heading">{{ __('Lainnya') }}</li>
                        <li class="mobile-nav-subitem"><a href="{{ route('katalog.perpustakaan') }}">{{ __('Perpustakaan') }}</a></li>
                        <li class="mobile-nav-subitem"><a href="{{ route('alumni') }}" class="{{ request()->is('alumni*') ? 'active' : '' }}">{{ __('Alumni') }}</a></li>
                    </ul>

                    <div class="mobile-nav-dock">
                        <form class="mobile-nav-search" action="{{ route('berita') }}" method="GET" role="search">
                            <label class="sr-only" for="km-mobile-search-input">{{ __('Cari berita') }}</label>
                            <input id="km-mobile-search-input" type="search" name="search" placeholder="{{ __('Cari berita...') }}" autocomplete="off">
                            <button type="submit" aria-label="{{ __('Cari') }}"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>

                        <div class="mobile-nav-utility">
                            <form class="mobile-nav-language" action="{{ route('language.switch', app()->getLocale() === 'id' ? 'en' : 'id') }}" method="POST">
                                @csrf
                                <button type="submit" class="km-language-toggle" data-language="{{ app()->getLocale() === 'id' ? 'id' : 'en' }}" aria-label="{{ app()->getLocale() === 'id' ? 'Switch to English' : 'Ganti ke Bahasa Indonesia' }}">
                                    <span class="km-language-toggle__option">{{ __('ID') }}</span>
                                    <span class="km-language-toggle__option">{{ __('EN') }}</span>
                                    <span class="km-language-toggle__indicator" aria-hidden="true"></span>
                                </button>
                            </form>

                            @auth
                                <a href="/home" class="mobile-nav-auth"><i class="fa fa-user" aria-hidden="true"></i>{{ __('Akun') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="mobile-nav-auth" data-login-modal-open aria-controls="km-login-modal"><i class="fa fa-sign-in" aria-hidden="true"></i>{{ __('Masuk') }}</a>
                            @endauth
                        </div>

                        <a class="mobile-nav-explore" href="{{ route('profile.sekolah') }}">
                            <i class="fa fa-compass" aria-hidden="true"></i>
                            {{ __('Jelajahi sekolah') }}
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </nav>
            </details>
        </div>
    </div>
</div>
<!-- Mobile Menu Area End -->
