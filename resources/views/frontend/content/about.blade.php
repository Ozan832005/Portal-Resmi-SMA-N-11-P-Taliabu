{{-- Latar bergerak: petak bersudut bulat (rounded square tiles) + cahaya lembut. --}}
<div class="about1-area">
    <div class="km-about-fx" aria-hidden="true"></div>
    <script src="{{ asset('Assets/Frontend/js/km-about.js') }}?v={{ filemtime(public_path('Assets/Frontend/js/km-about.js')) }}" defer></script>

    <div class="container km-about-shell">
        <div class="km-about">
            <div class="km-about-media">
                @if ($about->image)
                    <img src="{{asset('storage/images/about/' .$about->image)}}" alt="Suasana SMA Negeri 11 Pulau Taliabu" class="img-responsive">
                @else
                    <img src="{{asset('Assets/Frontend/img/about/1.jpg')}}" alt="SMA Negeri 11 Pulau Taliabu" class="img-responsive">
                @endif
                <div class="km-about-badge">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <span>{{ __('Kab. Pulau Taliabu, Maluku Utara') }}</span>
                </div>
            </div>

            <div class="km-about-copy">
                <h2 class="km-about-title">{{ $about->title ?: 'SMA Negeri 11 Pulau Taliabu' }}</h2>

                @if ($about->desc)
                    <div class="km-about-desc">
                        <p>{{ $about->desc }}</p>
                    </div>
                @endif

                <div class="km-about-actions">
                    <a href="{{route('profile.sekolah')}}" class="km-btn km-btn--primary">{{ __('Profil sekolah') }} <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                    <a href="{{route('visimisi.sekolah')}}" class="km-btn km-btn--ghost">{{ __('Visi & misi') }}</a>
                </div>
            </div>
        </div>

        <div class="km-about-location">
            <div class="km-about-location__info">
                <span class="km-about-location__pin" aria-hidden="true"><i class="fa fa-map-marker"></i></span>
                <div class="km-about-location__text">
                    <span class="km-about-location__label">{{ __('Lokasi sekolah') }}</span>
                    <strong class="km-about-location__value">{{ __('Kab. Pulau Taliabu, Maluku Utara') }}</strong>
                </div>
                <a class="km-about-location__link" href="https://www.google.com/maps/search/?api=1&query=SMA+Negeri+11+Pulau+Taliabu" target="_blank" rel="noopener">
                    {{ __('Lihat di Maps') }} <i class="fa fa-external-link" aria-hidden="true"></i>
                </a>
            </div>

            <div class="km-about-location__frame">
                <iframe
                    src="https://www.google.com/maps?q=SMA%20Negeri%2011%20Pulau%20Taliabu&output=embed"
                    title="{{ __('Peta lokasi SMA Negeri 11 Pulau Taliabu') }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
