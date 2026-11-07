@php
    $pengguna = $user ?? null;
    $statusSaatIni = old('status', $pengguna->status ?? 'Aktif');
@endphp

<div class="form-group">
    <label for="field-status">Status akun <span class="text-danger">*</span></label>
    <select id="field-status" name="status" class="form-control @error('status') is-invalid @enderror" required>
        <option value="Aktif" @selected($statusSaatIni == 'Aktif')>Aktif</option>
        <option value="Tidak Aktif" @selected($statusSaatIni == 'Tidak Aktif')>Tidak aktif</option>
    </select>
    @error('status')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
    <small class="km-form-hint">Akun tidak aktif tetap tersimpan, tetapi tidak bisa masuk ke dashboard.</small>
</div>
