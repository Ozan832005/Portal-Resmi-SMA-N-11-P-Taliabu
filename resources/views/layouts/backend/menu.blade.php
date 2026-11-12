@php
    $role = Auth::user()->role;

    // Grup menu dianggap terbuka bila salah satu halaman di dalamnya aktif,
    // supaya item aktif tetap terlihat tanpa harus diklik.
    $kontenAktif = request()->routeIs(
        'backend-berita.*',
        'backend-kategori-berita.*',
        'backend-event.*',
        'backend-about.*',
        'backend-imageslider.*',
        'backend-video.*',
        'backend-footer.*',
        'backend-fasilitas.*',
        'backend-footer-gallery.*'
    );
    $tentangAktif = request()->routeIs('backend-profile-sekolah.*', 'backend-visimisi.*');
    $penggunaAktif = request()->routeIs(
        'backend-pengguna-pengajar.*',
        'backend-pengguna-staf.*',
        'backend-pengguna-murid.*',
        'backend-pengguna-ppdb.*',
        'backend-pengguna-perpus.*'
    );
@endphp

<div id="main-menu-sidebar" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <span class="brand-logo" aria-hidden="true">
                        <img
                            src="{{ $schoolLogo ? asset('storage/images/logo/' . $schoolLogo) : asset('Assets/Frontend/img/favicon.png') }}"
                            alt="">
                    </span>
                    <span class="brand-text">
                        <span>SMA NEGERI 11</span>
                        <small>PULAU TALIABU</small>
                    </span>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc" data-ticon="disc"></i>
                </a>
            </li>
        </ul>

        {{-- Identitas operator drawer: hanya tampil di HP/tablet (≤1199px) --}}
        <div class="km-drawer-identity d-xl-none">
            <span class="km-drawer-avatar" aria-hidden="true">
                @if (Auth::user()->foto_profile == null)
                    <img src="{{ asset('Assets/Backend/images/user.png') }}" alt="">
                @else
                    <img src="{{ asset('storage/images/profile/' . Auth::user()->foto_profile) }}" alt="">
                @endif
            </span>
            <span class="km-drawer-identity-name">{{ Auth::user()->name }}</span>
            <span class="km-drawer-role">{{ $role }}</span>
        </div>
    </div>
    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('home') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Dashboard</span>
                </a>
            </li>

            @if ($role == 'Admin')
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Publikasi</span>
                </li>

                <li class="nav-item {{ $kontenAktif ? 'open sidebar-group-active' : '' }}">
                    <a class="d-flex align-items-center" href="javascript:void(0);">
                        <i data-feather="file-text"></i>
                        <span class="menu-title text-truncate">Berita & acara</span>
                    </a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('backend-berita.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-berita.index') }}">
                                <span class="menu-item text-truncate">Data berita</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-kategori-berita.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-kategori-berita.index') }}">
                                <span class="menu-item text-truncate">Kategori berita</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-event.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-event.index') }}">
                                <span class="menu-item text-truncate">Acara sekolah</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('backend-video-publikasi.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('backend-video-publikasi.index') }}">
                        <i data-feather="video"></i>
                        <span class="menu-title text-truncate">Video publikasi</span>
                    </a>
                </li>

                <li class="nav-item {{ $tentangAktif ? 'open sidebar-group-active' : '' }}">
                    <a class="d-flex align-items-center" href="javascript:void(0);">
                        <i data-feather="book-open"></i>
                        <span class="menu-title text-truncate">Profil & visi</span>
                    </a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('backend-profile-sekolah.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-profile-sekolah.index') }}">
                                <span class="menu-item text-truncate">Profil sekolah</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-visimisi.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-visimisi.index') }}">
                                <span class="menu-item text-truncate">Visi dan misi</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('backend-about.*', 'backend-imageslider.*', 'backend-video.*', 'backend-footer.*') ? 'open sidebar-group-active' : '' }}">
                    <a class="d-flex align-items-center" href="javascript:void(0);">
                        <i data-feather="globe"></i>
                        <span class="menu-title text-truncate">Halaman situs</span>
                    </a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('backend-about.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-about.index') }}">
                                <span class="menu-item text-truncate">Tentang</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-imageslider.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-imageslider.index') }}">
                                <span class="menu-item text-truncate">Gambar slider</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-video.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-video.index') }}">
                                <span class="menu-item text-truncate">Video</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-fasilitas.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-fasilitas.index') }}">
                                <span class="menu-item text-truncate">Fasilitas sekolah</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-footer-gallery.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-footer-gallery.index') }}">
                                <span class="menu-item text-truncate">Galeri footer</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-footer.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-footer.index') }}">
                                <span class="menu-item text-truncate">Footer</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Akademik</span>
                </li>

                <li class="nav-item {{ request()->routeIs('backend-kurikulum.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('backend-kurikulum.index') }}">
                        <i data-feather="layers"></i>
                        <span class="menu-title text-truncate">Kurikulum</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('backend-kegiatan.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('backend-kegiatan.index') }}">
                        <i data-feather="calendar"></i>
                        <span class="menu-title text-truncate">Kegiatan</span>
                    </a>
                </li>

                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Pengguna</span>
                </li>

                <li class="nav-item {{ $penggunaAktif ? 'open sidebar-group-active' : '' }}">
                    <a class="d-flex align-items-center" href="javascript:void(0);">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate">Akun pengguna</span>
                    </a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('backend-pengguna-pengajar.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-pengguna-pengajar.index') }}">
                                <span class="menu-item text-truncate">Pengajar</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-pengguna-staf.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-pengguna-staf.index') }}">
                                <span class="menu-item text-truncate">Staf</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-pengguna-murid.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-pengguna-murid.index') }}">
                                <span class="menu-item text-truncate">Murid</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-pengguna-ppdb.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-pengguna-ppdb.index') }}">
                                <span class="menu-item text-truncate">Petugas PPDB</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('backend-pengguna-perpus.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('backend-pengguna-perpus.index') }}">
                                <span class="menu-item text-truncate">Petugas perpustakaan</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Sistem</span>
                </li>

                <li class="nav-item {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('backup.index') }}">
                        <i data-feather="database"></i>
                        <span class="menu-title text-truncate">Cadangan data</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('settings') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('settings') }}">
                        <i data-feather="settings"></i>
                        <span class="menu-title text-truncate">Pengaturan</span>
                    </a>
                </li>

            @elseif ($role == 'Guru')
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Akademik</span>
                </li>

                <li class="nav-item {{ request()->routeIs('guru.kurikulum') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('guru.kurikulum') }}">
                        <i data-feather="layers"></i>
                        <span class="menu-title text-truncate">Kurikulum</span>
                    </a>
                </li>

            @elseif ($role == 'Perpustakaan')
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Sirkulasi</span>
                </li>

                <li class="nav-item {{ request()->routeIs('peminjam.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('peminjam.index') }}">
                        <i data-feather="repeat"></i>
                        <span class="menu-title text-truncate">Peminjaman</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('perpus/history-peminjam') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ url('perpus/history-peminjam') }}">
                        <i data-feather="clock"></i>
                        <span class="menu-title text-truncate">Riwayat peminjaman</span>
                    </a>
                </li>

                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Koleksi</span>
                </li>

                <li class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('books.index') }}">
                        <i data-feather="book"></i>
                        <span class="menu-title text-truncate">Buku</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('kategori.index') }}">
                        <i data-feather="tag"></i>
                        <span class="menu-title text-truncate">Kategori</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('publisher.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('publisher.index') }}">
                        <i data-feather="briefcase"></i>
                        <span class="menu-title text-truncate">Penerbit</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('author.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('author.index') }}">
                        <i data-feather="feather"></i>
                        <span class="menu-title text-truncate">Penulis</span>
                    </a>
                </li>

                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Anggota</span>
                </li>

                <li class="nav-item {{ request()->routeIs('member.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('member.index') }}">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate">Anggota perpustakaan</span>
                    </a>
                </li>

            @elseif ($role == 'Murid')
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Layanan saya</span>
                </li>

                <li class="nav-item {{ request()->routeIs('perpustakaan.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('perpustakaan.index') }}">
                        <i data-feather="book"></i>
                        <span class="menu-title text-truncate">Perpustakaan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('pembayaran.index') }}">
                        <i data-feather="credit-card"></i>
                        <span class="menu-title text-truncate">Pembayaran</span>
                    </a>
                </li>

            @elseif ($role == 'Bendahara')
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Keuangan</span>
                </li>

                <li class="nav-item">
                    <a class="d-flex align-items-center" href="{{ route('home') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate">Dashboard</span>
                    </a>
                </li>

            @elseif ($role == 'PPDB')
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Penerimaan</span>
                </li>

                <li class="nav-item {{ request()->routeIs('data-murid.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('data-murid.index') }}">
                        <i data-feather="user-plus"></i>
                        <span class="menu-title text-truncate">Data calon murid</span>
                    </a>
                </li>

            @elseif ($role == 'Guest')
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Pendaftaran</span>
                </li>

                <li class="nav-item {{ request()->routeIs('ppdb.form-pendaftaran') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('ppdb.form-pendaftaran') }}">
                        <i data-feather="clipboard"></i>
                        <span class="menu-title text-truncate">Formulir pendaftaran</span>
                    </a>
                </li>

            @else
                <li class="km-menu-section" role="presentation">
                    <span class="km-menu-section-label">Tautan</span>
                </li>

                <li class="nav-item">
                    <a class="d-flex align-items-center" href="{{ url('/') }}">
                        <i data-feather="globe"></i>
                        <span class="menu-title text-truncate">Situs sekolah</span>
                    </a>
                </li>
            @endif

            <li class="km-menu-section" role="presentation">
                <span class="km-menu-section-label">Akun</span>
            </li>

            <li class="nav-item {{ request()->routeIs('profile-settings.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('profile-settings.index') }}">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate">Profil saya</span>
                </a>
            </li>
        </ul>
    </div>
</div>
