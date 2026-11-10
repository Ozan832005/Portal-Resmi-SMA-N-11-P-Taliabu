@extends('layouts.backend.app')

@section('title')
    Pengaturan
@endsection

@section('content')
    <div class="content-wrapper container-xxl km-page km-settings-page">
        <div class="km-page-head">
            <div>
                <h2 class="km-page-title">Pengaturan</h2>
                <p class="km-page-sub">Atur cara Anda menerima pemberitahuan.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                Data pengaturan tidak valid. Periksa kembali isian Anda.
            </div>
        @endif

        <section class="km-settings-card" aria-labelledby="km-settings-notifications-title">
            <div class="km-settings-card__header">
                <span class="km-settings-card__icon" aria-hidden="true">
                    <i data-feather="bell"></i>
                </span>
                <div>
                    <h3 id="km-settings-notifications-title">Pemberitahuan</h3>
                    <p>Pilih saluran yang ingin digunakan untuk menerima informasi.</p>
                </div>
            </div>

            <form action="{{ url('settings/notifications', Auth::id()) }}" method="post">
                @csrf
                @method('PUT')

                <div class="km-settings-option">
                    <div>
                        <strong>Email</strong>
                        <p>Kirim pemberitahuan melalui alamat email akun Anda.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input
                            type="checkbox"
                            class="custom-control-input"
                            name="isEmail"
                            value="1"
                            id="email"
                            {{ optional(auth()->user()->setting)->isEmail == 1 ? 'checked' : '' }}
                        >
                        <label class="custom-control-label" for="email">
                            <span class="sr-only">Aktifkan pemberitahuan email</span>
                        </label>
                    </div>
                </div>

                <div class="km-settings-option km-settings-option--disabled">
                    <div>
                        <strong>WhatsApp</strong>
                        <p>Pemberitahuan WhatsApp belum tersedia.</p>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="isWhatsApp" disabled>
                        <label class="custom-control-label" for="isWhatsApp">
                            <span class="sr-only">Pemberitahuan WhatsApp belum tersedia</span>
                        </label>
                    </div>
                </div>

                <div class="km-settings-actions">
                    <button type="submit" class="btn btn-primary">Simpan pengaturan</button>
                    <button type="reset" class="btn btn-outline-secondary">Batalkan perubahan</button>
                </div>
            </form>
        </section>
    </div>
@endsection