@extends('layouts.backend.app')

@section('title')
    Dashboard
@endsection

@section('content')
@php
    $periode = trim(($month ?? \Carbon\Carbon::now()->locale('id')->translatedFormat('F')) . ' ' . ($year ?? date('Y')));
    $tanggalHariIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y');
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Dashboard SPP</h1>
            <p class="km-page-sub">Rekap tagihan sekolah. Periode berjalan {{ $periode }}.</p>
        </div>
        <p class="km-page-date">{{ $tanggalHariIni }}</p>
    </header>

    <section class="km-panel">
        <div class="km-panel-head">
            <h2 class="km-panel-title">Status tagihan {{ $periode }}</h2>
            <a class="km-link" href="{{ route('spp.murid.index') }}">Buka pembayaran murid</a>
        </div>
        <div class="km-figures">
            <a class="km-figure" href="{{ route('spp.murid.index') }}">
                <span class="km-figure-value">{{ $paid ?? 0 }}</span>
                <span class="km-figure-label">Tagihan lunas</span>
            </a>
            <a class="km-figure" href="{{ route('spp.murid.index') }}">
                <span class="km-figure-value">{{ $unpaid ?? 0 }}</span>
                <span class="km-figure-label">Belum lunas</span>
            </a>
        </div>
        <p class="km-figure-note">
            Angka dihitung dari seluruh tagihan bulanan yang tercatat. Konfirmasi pembayaran
            dilakukan dari halaman pembayaran murid setelah bukti transfer diperiksa.
        </p>
    </section>

    <div class="row">
        <div class="col-lg-8">
            <section class="km-panel">
                <div class="km-panel-head">
                    <h2 class="km-panel-title">Pintasan</h2>
                </div>
                <div class="km-quick-grid">
                    <a class="km-quick" href="{{ route('spp.murid.index') }}">
                        <i data-feather="credit-card"></i>
                        <span>Pembayaran murid</span>
                    </a>
                    <a class="km-quick" href="{{ route('profile-settings.index') }}">
                        <i data-feather="user"></i>
                        <span>Profil &amp; password</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
