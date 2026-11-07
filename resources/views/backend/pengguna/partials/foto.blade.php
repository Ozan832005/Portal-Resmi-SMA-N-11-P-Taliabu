@php
    $fotoLama = $foto ?? null;
    $wajib = $wajib ?? false;
@endphp

<div class="km-form-preview mb-2">
    @if ($fotoLama)
        <img src="{{ asset('storage/images/profile/'.$fotoLama) }}" alt="Foto profil saat ini">
    @else
        <span class="km-avatar km-avatar--initials km-avatar--lg">Foto</span>
    @endif
    <p>
        @if ($fotoLama)
            Foto saat ini. Unggah berkas baru untuk menggantinya.
        @else
            Belum ada foto. Unggah foto wajah agar mudah dikenali pada daftar dan dashboard.
        @endif
    </p>
</div>

<div class="form-group mb-0">
    <label for="field-foto">
        Unggah foto
        @if ($wajib)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input id="field-foto" type="file" name="foto_profile" accept="image/*"
           class="form-control @error('foto_profile') is-invalid @enderror"
           @if ($wajib) required @endif>
    <small class="km-form-hint">JPG atau PNG, maksimal 1 MB.</small>
    @error('foto_profile')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
