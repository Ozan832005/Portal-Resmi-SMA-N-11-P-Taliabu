<section class="km-facts-area" aria-label="{{ __('Ringkasan sekolah') }}">
    <div class="container">
        <div class="km-facts-heading">
            <span class="km-facts-heading__eyebrow">{{ __('SMA Negeri 11 Pulau Taliabu') }}</span>
            <h2>{{ __('Sekolah dalam angka') }}</h2>
            <p>{{ __('Ringkasan tenaga pendidik, peserta didik, dan fasilitas sekolah.') }}</p>
        </div>
        <div class="km-facts">
            <div class="km-fact">
                <div class="km-fact-icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
                <div class="km-fact-content">
                    <span class="km-fact-kicker">{{ __('Tenaga pendidik') }}</span>
                    <span class="km-fact-value" data-target="{{ (int) ($jumlahGuru ?? 0) }}">{{ number_format((int) ($jumlahGuru ?? 0), 0, ',', '.') }}</span>
                    <span class="km-fact-label">{{ __('Guru terdaftar') }}</span>
                </div>
                <span class="km-fact-status" aria-hidden="true"><i class="fa fa-check" aria-hidden="true"></i></span>
            </div>
            <div class="km-fact">
                <div class="km-fact-icon"><i class="fa fa-users" aria-hidden="true"></i></div>
                <div class="km-fact-content">
                    <span class="km-fact-kicker">{{ __('Peserta didik aktif') }}</span>
                    <span class="km-fact-value" data-target="{{ (int) ($jumlahSiswa ?? 0) }}">{{ number_format((int) ($jumlahSiswa ?? 0), 0, ',', '.') }}</span>
                    <span class="km-fact-label">{{ __('Siswa terdaftar') }}</span>
                </div>
                <span class="km-fact-status" aria-hidden="true"><i class="fa fa-check" aria-hidden="true"></i></span>
            </div>
            <div class="km-fact">
                <div class="km-fact-icon"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                <div class="km-fact-content">
                    <span class="km-fact-kicker">{{ __('Sarana sekolah') }}</span>
                    <span class="km-fact-value" data-target="{{ (int) ($fasilitas->count() ?? 0) }}">{{ number_format((int) ($fasilitas->count() ?? 0), 0, ',', '.') }}</span>
                    <span class="km-fact-label">{{ __('Fasilitas sekolah') }}</span>
                </div>
                <span class="km-fact-status" aria-hidden="true"><i class="fa fa-check" aria-hidden="true"></i></span>
            </div>
        </div>
    </div>
</section>

@if (isset($fasilitas) && $fasilitas->isNotEmpty())
    <section class="km-facilities-wrap" aria-label="{{ __('Fasilitas sekolah') }}">
        <div class="container">
            <div class="km-facilities-head">
                <h2>{{ __('Fasilitas sekolah') }}</h2>
                <span>{{ __(':count fasilitas terdaftar', ['count' => $fasilitas->count()]) }}</span>
            </div>
            <div class="km-facilities">
                @foreach ($fasilitas as $item)
                    <div class="km-facility">
                        <div class="km-facility-thumb">
                            @if ($item->image)
                                <img src="{{ asset('storage/images/fasilitas/' . $item->image) }}" alt="{{ $item->nama }}" loading="lazy">
                            @else
                                <i class="fa fa-building-o" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div>
                            <span class="km-facility-name">{{ $item->nama }}</span>
                            @if ($item->desc)
                                <span class="km-facility-desc">{{ \Illuminate\Support\Str::limit($item->desc, 70) }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
