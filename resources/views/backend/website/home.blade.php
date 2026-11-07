@extends('layouts.backend.app')

@section('title')
    Dashboard
@endsection

@section('content')
@php
    $role = Auth::user()->role;
    $isAdmin = $role === 'Admin';
    $daftarBerita = $berita ?? collect();
    $tanggalHariIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y');
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Dashboard</h1>
            <p class="km-page-sub">
                Selamat datang, {{ Auth::user()->name }}.
                @if ($isAdmin)
                    Ringkasan pengguna, konten, dan perpustakaan sekolah.
                @else
                    Agenda sekolah dan pintasan yang bisa Anda akses.
                @endif
            </p>
        </div>
        <p class="km-page-date">{{ $tanggalHariIni }}</p>
    </header>

    @if ($isAdmin)
        <div class="row">
            <div class="col-xl-8">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Ringkasan sekolah</h2>
                    </div>
                    <div class="km-figures">
                        <a class="km-figure" href="{{ route('backend-pengguna-pengajar.index') }}">
                            <span class="km-figure-value">{{ $guru ?? 0 }}</span>
                            <span class="km-figure-label">Pengajar aktif</span>
                        </a>
                        <a class="km-figure" href="{{ route('backend-pengguna-murid.index') }}">
                            <span class="km-figure-value">{{ $murid ?? 0 }}</span>
                            <span class="km-figure-label">Murid aktif</span>
                        </a>
                        <div class="km-figure">
                            <span class="km-figure-value">{{ $alumni ?? 0 }}</span>
                            <span class="km-figure-label">Alumni aktif</span>
                        </div>
                        <a class="km-figure" href="{{ route('backend-event.index') }}">
                            <span class="km-figure-value">{{ $acara ?? 0 }}</span>
                            <span class="km-figure-label">Acara aktif</span>
                        </a>
                    </div>
                    <p class="km-figure-note">
                        Dihitung dari akun berstatus aktif dan acara yang sedang berjalan.
                        Alumni belum punya daftar tersendiri, angkanya diambil dari akun alumni.
                    </p>
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Perpustakaan</h2>
                    </div>
                    <div class="km-figures km-figures--three">
                        <div class="km-figure">
                            <span class="km-figure-value">{{ $book ?? 0 }}</span>
                            <span class="km-figure-label">Stok buku</span>
                        </div>
                        <div class="km-figure">
                            <span class="km-figure-value">{{ $borrow ?? 0 }}</span>
                            <span class="km-figure-label">Peminjaman berjalan</span>
                        </div>
                        <div class="km-figure">
                            <span class="km-figure-value">{{ $member ?? 0 }}</span>
                            <span class="km-figure-label">Anggota aktif</span>
                        </div>
                    </div>
                    <p class="km-figure-note">
                        Stok buku adalah jumlah seluruh stok judul. Peminjaman berjalan yang belum dikembalikan.
                    </p>
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Berita terbaru</h2>
                        <a class="km-link" href="{{ route('backend-berita.index') }}">Lihat semua berita</a>
                    </div>

                    @if ($daftarBerita->isEmpty())
                        <div class="km-empty">
                            <p class="km-empty-title">Belum ada berita</p>
                            <p class="km-empty-text">Berita yang Anda terbitkan akan muncul di sini dan pada situs sekolah.</p>
                            <a class="btn btn-primary btn-sm" href="{{ route('backend-berita.create') }}">Tambah berita</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Berita</th>
                                        <th scope="col">Kategori</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($daftarBerita as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->thumbnail)
                                                        <img src="{{ asset('storage/images/berita/'.$item->thumbnail) }}"
                                                             alt="Thumbnail {{ $item->title }}" class="km-thumb mr-2">
                                                    @else
                                                        <span class="km-thumb km-thumb--empty mr-2">Tanpa gambar</span>
                                                    @endif
                                                    <span>{{ $item->title }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $item->kategori->nama ?? 'Tanpa kategori' }}</td>
                                            <td>
                                                <span class="km-status {{ $item->is_active == '0' ? 'km-status--live' : 'km-status--draft' }}">
                                                    {{ $item->is_active == '0' ? 'Terbit' : 'Draf' }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <a class="btn btn-sm btn-success" href="{{ route('backend-berita.edit', $item->id) }}">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>

            <div class="col-xl-4">
                @include('backend.website.partials.agenda', [
                    'acara' => $event ?? null,
                    'tautanAcara' => route('backend-event.index'),
                ])

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Pintasan</h2>
                    </div>
                    <div class="km-quick-grid">
                        <a class="km-quick" href="{{ route('backend-berita.create') }}">
                            <i data-feather="edit-3"></i>
                            <span>Tambah berita</span>
                        </a>
                        <a class="km-quick" href="{{ route('backend-event.create') }}">
                            <i data-feather="calendar"></i>
                            <span>Tambah acara</span>
                        </a>
                        <a class="km-quick" href="{{ route('backend-pengguna-murid.index') }}">
                            <i data-feather="users"></i>
                            <span>Data murid</span>
                        </a>
                        <a class="km-quick" href="{{ route('settings') }}">
                            <i data-feather="settings"></i>
                            <span>Pengaturan</span>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-7">
                @include('backend.website.partials.agenda', ['acara' => $event ?? null])
            </div>

            <div class="col-lg-5">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Pintasan</h2>
                    </div>
                    <div class="km-quick-grid">
                        <a class="km-quick" href="{{ route('profile-settings.index') }}">
                            <i data-feather="user"></i>
                            <span>Profil &amp; password</span>
                        </a>
                        <a class="km-quick" href="{{ url('/') }}">
                            <i data-feather="globe"></i>
                            <span>Situs sekolah</span>
                        </a>
                    </div>
                    <p class="km-figure-note">
                        Anda masuk sebagai {{ $role }}. Menu lain muncul sesuai hak akses akun Anda.
                    </p>
                </section>
            </div>
        </div>
    @endif
</div>
@endsection
