@extends('layouts.backend.app')

@section('title', 'Edit kurikulum')

@section('content')
    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Edit kurikulum</h1>
                <p class="km-page-sub">Perbarui materi yang ditampilkan pada halaman Kurikulum situs sekolah.</p>
            </div>
            <a class="btn btn-outline-primary" href="{{ route('backend-kurikulum.index') }}">Kembali</a>
        </header>

        <section class="km-panel">
            <form action="{{ route('backend-kurikulum.update', $curriculum) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('backend.website.kurikulum._form', ['curriculum' => $curriculum, 'submitLabel' => 'Simpan perubahan'])
            </form>
        </section>
    </div>
@endsection
