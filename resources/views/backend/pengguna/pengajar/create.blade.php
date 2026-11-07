@extends('layouts.backend.app')

@section('title')
    Tambah Pengajar
@endsection

@section('content')
<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Tambah pengajar</h1>
            <p class="km-page-sub">Isi identitas, data kepegawaian, dan foto profil. Akun langsung aktif setelah disimpan.</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-pengajar.index') }}">Kembali ke daftar</a>
    </header>

    @if ($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @endif

    <form action="{{ route('backend-pengguna-pengajar.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xl-8">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Identitas</h2>
                    </div>
                    @include('backend.pengguna.partials.identitas', ['user' => null])
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Data kepegawaian</h2>
                    </div>
                    @include('backend.pengguna.partials.kepegawaian', ['detail' => null, 'denganMengajar' => true])
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Media sosial</h2>
                    </div>
                    @include('backend.pengguna.partials.sosial', ['detail' => null])
                </section>

                <div class="km-form-actions">
                    <button class="btn btn-primary" type="submit">Simpan pengajar</button>
                    <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-pengajar.index') }}">Batal</a>
                </div>
            </div>

            <div class="col-xl-4">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Foto profil</h2>
                    </div>
                    @include('backend.pengguna.partials.foto', ['foto' => null, 'wajib' => true])
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Akun</h2>
                    </div>
                    <p class="km-figure-note mb-0">
                        Sistem membuat kata sandi awal dan peran Guru secara otomatis. Minta pengajar
                        mengganti kata sandi lewat menu Profil saya setelah masuk pertama kali.
                    </p>
                </section>
            </div>
        </div>
    </form>
</div>
@endsection
