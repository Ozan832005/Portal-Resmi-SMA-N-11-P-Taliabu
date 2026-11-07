@php
    $detailPengguna = $detail ?? null;
    $denganMengajar = $denganMengajar ?? false;
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="field-nip">NIP <span class="text-danger">*</span></label>
            <input id="field-nip" type="text" inputmode="numeric" name="nip"
                   value="{{ old('nip', $detailPengguna->nip ?? '') }}" placeholder="Nomor induk pegawai"
                   class="form-control @error('nip') is-invalid @enderror" required>
            @error('nip')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    @if ($denganMengajar)
        <div class="col-md-6">
            <div class="form-group">
                <label for="field-mengajar">Mata pelajaran <span class="text-danger">*</span></label>
                <input id="field-mengajar" type="text" name="mengajar"
                       value="{{ old('mengajar', $detailPengguna->mengajar ?? '') }}"
                       placeholder="Contoh: Matematika" list="daftar-mapel"
                       class="form-control @error('mengajar') is-invalid @enderror" required>
                <datalist id="daftar-mapel">
                    <option value="Matematika"></option>
                    <option value="Bahasa Indonesia"></option>
                    <option value="Bahasa Inggris"></option>
                    <option value="Fisika"></option>
                    <option value="Kimia"></option>
                    <option value="Biologi"></option>
                    <option value="Sejarah"></option>
                    <option value="Geografi"></option>
                    <option value="Ekonomi"></option>
                    <option value="Sosiologi"></option>
                    <option value="Pendidikan Agama"></option>
                    <option value="PJOK"></option>
                    <option value="Seni Budaya"></option>
                    <option value="Informatika"></option>
                </datalist>
                @error('mengajar')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
                <small class="km-form-hint">Bisa diisi bebas, saran mapel di atas hanya bantuan.</small>
            </div>
        </div>
    @endif
</div>
