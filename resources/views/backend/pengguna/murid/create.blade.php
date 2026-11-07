@extends('layouts.backend.app')

@section('title')
    Tambah Murid
@endsection

@section('content')
<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Tambah murid</h1>
            <p class="km-page-sub">Akun baru dibuat sebagai calon murid, lalu bisa diubah menjadi murid setelah terverifikasi.</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-murid.index') }}">Kembali ke daftar</a>
    </header>

    @if ($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @endif

    <form action="{{ route('backend-pengguna-murid.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xl-8">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Identitas</h2>
                    </div>

                    @include('backend.pengguna.partials.identitas', ['user' => null])

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="field-kelamin">Jenis kelamin <span class="text-danger">*</span></label>
                                <select id="field-kelamin" name="kelamin" class="form-control @error('kelamin') is-invalid @enderror" required>
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="Laki-laki" @selected(old('kelamin') == 'Laki-laki')>Laki-laki</option>
                                    <option value="Perempuan" @selected(old('kelamin') == 'Perempuan')>Perempuan</option>
                                </select>
                                @error('kelamin')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </section>

                <div class="km-form-actions">
                    <button class="btn btn-primary" type="submit">Simpan murid</button>
                    <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-murid.index') }}">Batal</a>
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

                    <div class="form-group mb-0">
                        <label for="field-password">Kata sandi awal <span class="text-danger">*</span></label>
                        <input id="field-password" type="password" name="password" autocomplete="new-password"
                               minlength="8" placeholder="Minimal 8 karakter"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <small class="km-form-hint">Berikan kata sandi ini kepada murid. Mereka bisa menggantinya lewat menu Profil saya.</small>
                    </div>

                    <p class="km-figure-note">
                        Peran akun diset sebagai calon murid. Ubah menjadi murid dari halaman edit setelah
                        berkas pendaftaran diverifikasi.
                    </p>
                </section>
            </div>
        </div>
    </form>
</div>
@endsection
