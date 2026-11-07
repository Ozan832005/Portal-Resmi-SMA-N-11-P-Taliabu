@extends('layouts.backend.app')

@section('title')
    Dashboard
@endsection

@section('content')
@php
    $role = Auth::user()->role;
    $tanggalHariIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y');
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Dashboard</h1>
            <p class="km-page-sub">
                @if ($role == 'PPDB')
                    Pantau pendaftar tahun ini dan data yang masih perlu diverifikasi.
                @else
                    Lengkapi data pendaftaran Anda, lalu unggah berkas yang diminta.
                @endif
            </p>
        </div>
        <p class="km-page-date">{{ $tanggalHariIni }}</p>
    </header>

    @if ($role == 'PPDB')
        <section class="km-panel">
            <div class="km-panel-head">
                <h2 class="km-panel-title">Ringkasan penerimaan</h2>
                <a class="km-link" href="{{ route('data-murid.index') }}">Buka data calon murid</a>
            </div>
            <div class="km-figures">
                <a class="km-figure" href="{{ route('data-murid.index') }}">
                    <span class="km-figure-value">{{ $register ?? 0 }}</span>
                    <span class="km-figure-label">Pendaftar tahun ini</span>
                </a>
                <a class="km-figure" href="{{ route('data-murid.index') }}">
                    <span class="km-figure-value">{{ $needVerif ?? 0 }}</span>
                    <span class="km-figure-label">Perlu verifikasi</span>
                </a>
            </div>
            <p class="km-figure-note">
                Pendaftar dihitung dari berkas tahun berjalan yang belum menjadi murid atau ditolak.
                Data yang perlu diverifikasi adalah pendaftar tanpa NISN.
            </p>
        </section>

        <section class="km-panel">
            <div class="km-panel-head">
                <h2 class="km-panel-title">Pintasan</h2>
            </div>
            <div class="km-quick-grid">
                <a class="km-quick" href="{{ route('data-murid.index') }}">
                    <i data-feather="user-plus"></i>
                    <span>Data calon murid</span>
                </a>
                <a class="km-quick" href="{{ route('profile-settings.index') }}">
                    <i data-feather="user"></i>
                    <span>Profil &amp; password</span>
                </a>
            </div>
        </section>
    @else
        <div class="row">
            <div class="col-lg-7">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Pendaftaran Anda</h2>
                    </div>
                    <p class="km-figure-note">
                        Isi formulir pendaftaran sampai selesai. Setelah data murid dan orang tua
                        terisi, unggah berkas persyaratan agar pendaftaran bisa diverifikasi panitia.
                    </p>
                    <div class="km-quick-grid">
                        <a class="km-quick" href="{{ route('ppdb.form-pendaftaran') }}">
                            <i data-feather="clipboard"></i>
                            <span>Formulir pendaftaran</span>
                        </a>
                        <a class="km-quick" href="{{ route('profile-settings.index') }}">
                            <i data-feather="user"></i>
                            <span>Profil &amp; password</span>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    @endif
</div>
@endsection
