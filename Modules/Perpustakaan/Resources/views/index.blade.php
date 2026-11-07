@extends('layouts.backend.app')

@section('title')
    Dashboard
@endsection

@section('content')
@php
    $tanggalHariIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y');
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Dashboard perpustakaan</h1>
            <p class="km-page-sub">Ringkasan koleksi, anggota, dan sirkulasi yang sedang berjalan.</p>
        </div>
        <p class="km-page-date">{{ $tanggalHariIni }}</p>
    </header>

    <section class="km-panel">
        <div class="km-panel-head">
            <h2 class="km-panel-title">Ringkasan</h2>
        </div>
        <div class="km-figures">
            <a class="km-figure" href="{{ route('books.index') }}">
                <span class="km-figure-value">{{ $book ?? 0 }}</span>
                <span class="km-figure-label">Stok buku</span>
            </a>
            <a class="km-figure" href="{{ route('member.index') }}">
                <span class="km-figure-value">{{ $members ?? 0 }}</span>
                <span class="km-figure-label">Anggota terdaftar</span>
            </a>
            <a class="km-figure" href="{{ route('peminjam.index') }}">
                <span class="km-figure-value">{{ $borrow ?? 0 }}</span>
                <span class="km-figure-label">Peminjaman berjalan</span>
            </a>
            <a class="km-figure" href="{{ route('member.index') }}">
                <span class="km-figure-value">{{ $member ?? 0 }}</span>
                <span class="km-figure-label">Anggota aktif</span>
            </a>
        </div>
        <p class="km-figure-note">
            Stok buku dihitung dari total stok seluruh judul. Peminjaman berjalan adalah pinjaman
            yang belum dikembalikan.
        </p>
    </section>

    <div class="row">
        <div class="col-lg-8">
            <section class="km-panel">
                <div class="km-panel-head">
                    <h2 class="km-panel-title">Pintasan</h2>
                </div>
                <div class="km-quick-grid">
                    <a class="km-quick" href="{{ route('books.index') }}">
                        <i data-feather="book"></i>
                        <span>Kelola buku</span>
                    </a>
                    <a class="km-quick" href="{{ route('peminjam.index') }}">
                        <i data-feather="repeat"></i>
                        <span>Peminjaman</span>
                    </a>
                    <a class="km-quick" href="{{ route('member.index') }}">
                        <i data-feather="users"></i>
                        <span>Anggota</span>
                    </a>
                    <a class="km-quick" href="{{ route('kategori.index') }}">
                        <i data-feather="tag"></i>
                        <span>Kategori</span>
                    </a>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <section class="km-panel">
                <div class="km-panel-head">
                    <h2 class="km-panel-title">Catatan sirkulasi</h2>
                </div>
                <p class="km-figure-note">
                    Catat peminjaman baru dari menu Peminjaman. Buku yang dikembalikan perlu
                    diperbarui statusnya agar angka peminjaman berjalan tetap akurat.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
