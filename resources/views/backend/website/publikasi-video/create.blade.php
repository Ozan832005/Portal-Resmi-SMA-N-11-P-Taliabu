@extends('layouts.backend.app')

@section('title', 'Tambah video publikasi')

@section('content')
    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Tambah video publikasi</h1>
                <p class="km-page-sub">Video yang aktif akan tampil pada halaman Publikasi.</p>
            </div>
            <a class="btn btn-outline-primary" href="{{ route('backend-video-publikasi.index') }}">Kembali</a>
        </header>

        <section class="km-panel">
            <form action="{{ route('backend-video-publikasi.store') }}" method="post">
                @csrf
                @include('backend.website.publikasi-video._form', ['publicationVideo' => null, 'submitLabel' => 'Simpan video'])
            </form>
        </section>
    </div>
@endsection
