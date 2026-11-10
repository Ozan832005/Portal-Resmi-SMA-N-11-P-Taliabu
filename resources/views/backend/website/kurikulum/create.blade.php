@extends('layouts.backend.app')

@section('title', 'Tambah kurikulum')

@section('content')
    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Tambah kurikulum</h1>
                <p class="km-page-sub">Materi aktif akan ditampilkan pada halaman Kurikulum situs sekolah.</p>
            </div>
            <a class="btn btn-outline-primary" href="{{ route('backend-kurikulum.index') }}">Kembali</a>
        </header>

        <section class="km-panel">
            <form action="{{ route('backend-kurikulum.store') }}" method="post">
                @csrf
                @include('backend.website.kurikulum._form', ['curriculum' => null, 'submitLabel' => 'Simpan kurikulum'])
            </form>
        </section>
    </div>
@endsection
