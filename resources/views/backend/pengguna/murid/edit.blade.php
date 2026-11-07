@extends('layouts.backend.app')

@section('title')
    Edit Murid
@endsection

@section('content')
@php
    $statusSaatIni = old('status', $murid->status ?? '');
    $peranSaatIni = old('role', $murid->role ?? 'Guest');
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Edit murid</h1>
            <p class="km-page-sub">Mengubah akun {{ $murid->name }} ({{ $murid->email }}).</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-murid.index') }}">Kembali ke daftar</a>
    </header>

    @if ($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @endif

    <form action="{{ route('backend-pengguna-murid.update', $murid->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Identitas</h2>
                    </div>
                    @include('backend.pengguna.partials.identitas', ['user' => $murid])
                </section>

                <div class="km-form-actions">
                    <button class="btn btn-primary" type="submit">Simpan perubahan</button>
                    <a class="btn btn-outline-primary" href="{{ route('backend-pengguna-murid.index') }}">Batal</a>
                </div>
            </div>

            <div class="col-xl-4">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Status &amp; peran</h2>
                    </div>

                    <div class="form-group">
                        <label for="field-status">Status akun <span class="text-danger">*</span></label>
                        <select id="field-status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="" disabled @selected($statusSaatIni === '')>Pilih status</option>
                            <option value="Aktif" @selected($statusSaatIni == 'Aktif')>Aktif</option>
                            <option value="Tidak Aktif" @selected($statusSaatIni == 'Tidak Aktif')>Tidak aktif</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="field-role">Peran <span class="text-danger">*</span></label>
                        <select id="field-role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="Guest" @selected($peranSaatIni == 'Guest')>Calon murid</option>
                            <option value="Murid" @selected($peranSaatIni == 'Murid')>Murid</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <small class="km-form-hint">
                            Mengubah peran ikut mengganti hak akses akun. Calon murid hanya bisa melengkapi pendaftaran.
                        </small>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>
@endsection
