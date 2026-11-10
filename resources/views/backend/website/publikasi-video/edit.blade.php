@extends('layouts.backend.app')

@section('title', 'Edit video publikasi')

@section('content')
    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Edit video publikasi</h1>
                <p class="km-page-sub">Perbarui tautan, keterangan, atau status video publikasi.</p>
            </div>
            <a class="btn btn-outline-primary" href="{{ route('backend-video-publikasi.index') }}">Kembali</a>
        </header>

        <section class="km-panel">
            <form action="{{ route('backend-video-publikasi.update', $publicationVideo) }}" method="post">
                @csrf
                @method('PUT')
                @include('backend.website.publikasi-video._form', ['publicationVideo' => $publicationVideo, 'submitLabel' => 'Simpan perubahan'])
            </form>
        </section>
    </div>
@endsection
