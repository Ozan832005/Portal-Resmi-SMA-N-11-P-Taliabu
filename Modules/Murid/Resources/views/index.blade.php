@extends('layouts.backend.app')

@section('title')
    Dashboard
@endsection

@section('content')
@php
    $tanggalHariIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y');
    $akun = Auth::user();
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Dashboard</h1>
            <p class="km-page-sub">Halo, {{ $akun->name }}. Ringkasan peminjaman perpustakaan dan pembayaran Anda.</p>
        </div>
        <p class="km-page-date">{{ $tanggalHariIni }}</p>
    </header>

    <div class="row">
        <div class="col-xl-7">
            <section class="km-panel">
                <div class="km-panel-head">
                    <h2 class="km-panel-title">Perpustakaan saya</h2>
                    <a class="km-link" href="{{ route('perpustakaan.index') }}">Buka perpustakaan</a>
                </div>
                <div class="km-figures">
                    <div class="km-figure">
                        <span class="km-figure-value">{{ $pinjam ?? 0 }}</span>
                        <span class="km-figure-label">Total peminjaman</span>
                    </div>
                    <div class="km-figure">
                        <span class="km-figure-value">{{ $lateness ?? 0 }}</span>
                        <span class="km-figure-label">Belum dikembalikan</span>
                    </div>
                </div>
                <p class="km-figure-note">
                    Total peminjaman mencakup seluruh riwayat. Buku yang belum dikembalikan sebaiknya
                    diselesaikan sebelum meminjam judul baru.
                </p>
            </section>

            <section class="km-panel">
                <div class="km-panel-head">
                    <h2 class="km-panel-title">Pembayaran</h2>
                </div>
                <p class="km-figure-note">
                    Tagihan SPP dan status pembayaran dibuka di halaman pembayaran. Unggah bukti
                    pembayaran dari sana agar bisa diverifikasi bendahara.
                </p>
                <div class="km-quick-grid">
                    <a class="km-quick" href="{{ route('pembayaran.index') }}">
                        <i data-feather="credit-card"></i>
                        <span>Lihat pembayaran</span>
                    </a>
                    <a class="km-quick" href="{{ route('profile-settings.index') }}">
                        <i data-feather="user"></i>
                        <span>Profil &amp; password</span>
                    </a>
                </div>
            </section>
        </div>

        <div class="col-xl-5">
            @include('backend.website.partials.agenda', ['acara' => $event ?? null])
        </div>
    </div>
</div>
@endsection
