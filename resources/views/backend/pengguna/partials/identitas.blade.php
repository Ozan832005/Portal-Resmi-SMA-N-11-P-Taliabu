@php
    $pengguna = $user ?? null;
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="field-name">Nama lengkap <span class="text-danger">*</span></label>
            <input id="field-name" type="text" name="name" value="{{ old('name', $pengguna->name ?? '') }}"
                   placeholder="Nama sesuai data sekolah" autocomplete="name"
                   class="form-control @error('name') is-invalid @enderror" required>
            @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="field-email">Email <span class="text-danger">*</span></label>
            <input id="field-email" type="email" name="email" value="{{ old('email', $pengguna->email ?? '') }}"
                   placeholder="nama@sekolah.sch.id" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
            <small class="km-form-hint">Email dipakai untuk masuk dan menerima pemberitahuan.</small>
        </div>
    </div>
</div>
