@extends('layouts.backend.app')

@section('title')
    Edit Pengajar
@endsection

@section('content')
<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Edit pengajar</h1>
            <p class="km-page-sub">Mengubah akun {{ $pengajar->name }} ({{ $pengajar->email }}).</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-pengajar.index') }}">Kembali ke daftar</a>
    </header>

    @if ($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @endif

    <form action="{{ route('backend-pengguna-pengajar.update', $pengajar->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Identitas</h2>
                    </div>
                    @include('backend.pengguna.partials.identitas', ['user' => $pengajar])
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Data kepegawaian</h2>
                    </div>
                    @include('backend.pengguna.partials.kepegawaian', [
                        'detail' => $pengajar->userDetail,
                        'denganMengajar' => true,
                    ])
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Media sosial</h2>
                    </div>
                    @include('backend.pengguna.partials.sosial', ['detail' => $pengajar->userDetail])
                </section>

                <div class="km-form-actions">
                    <button class="btn btn-primary" type="submit">Simpan perubahan</button>
                    <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-pengajar.index') }}">Batal</a>
                </div>
            </div>

            <div class="col-xl-4">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Foto profil</h2>
                    </div>
                    @include('backend.pengguna.partials.foto', [
                        'foto' => $pengajar->foto_profile,
                        'wajib' => false,
                    ])
                </section>

                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Status akun</h2>
                    </div>
                    @include('backend.pengguna.partials.status', ['user' => $pengajar])
                </section>
            </div>
        </div>
    </form>
</div>
@endsection
