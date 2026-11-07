@php
    $detailPengguna = $detail ?? null;
    $tautan = [
        'website' => ['label' => 'Website', 'placeholder' => 'https://contoh.sch.id'],
        'linkedln' => ['label' => 'LinkedIn', 'placeholder' => 'https://linkedin.com/in/nama'],
        'instagram' => ['label' => 'Instagram', 'placeholder' => '@nama atau tautan'],
        'facebook' => ['label' => 'Facebook', 'placeholder' => 'Tautan profil Facebook'],
        'youtube' => ['label' => 'YouTube', 'placeholder' => 'Tautan kanal YouTube'],
        'twitter' => ['label' => 'Twitter', 'placeholder' => '@nama'],
    ];
@endphp

<div class="row">
    @foreach ($tautan as $namaField => $info)
        <div class="col-md-4">
            <div class="form-group">
                <label for="field-{{ $namaField }}">{{ $info['label'] }}</label>
                <input id="field-{{ $namaField }}" type="text" name="{{ $namaField }}"
                       value="{{ old($namaField, $detailPengguna->{$namaField} ?? '') }}"
                       placeholder="{{ $info['placeholder'] }}"
                       class="form-control @error($namaField) is-invalid @enderror">
                @error($namaField)
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
    @endforeach
</div>
<p class="km-form-hint mb-0">Semua kolom media sosial opsional. Tautan yang diisi bisa tampil pada halaman profil sekolah.</p>
