@php
    $heroSlides = isset($slider) ? $slider : collect();

    // Slogan pada hero. Pisahkan beberapa varian dengan tanda "|" bila ingin
    // bergantian; animasi ketik-hapus-ulang ditangani Assets/Frontend/js/km-hero.js
    $heroSlogan = __('SMA NEGERI 11 PULAU TALIABU');
@endphp

{{-- Lapisan latar hero: grid berjalan + cahaya biru mengikuti kursor. --}}
<div class="km-hero-fx" data-hero-fx aria-hidden="true"></div>

<section class="km-hero {{ $heroSlides->isEmpty() ? 'km-hero--empty' : '' }}" data-km-hero data-slogan="{{ $heroSlogan }}">
    <div class="container">
        <div class="km-hero__inner">
            <div class="km-hero__copy">
                <p class="km-hero__eyebrow">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    {{ __('Kabupaten Pulau Taliabu, Maluku Utara') }}
                </p>

                @if ($heroSlides->isNotEmpty())
                    @foreach ($heroSlides as $key => $slide)
                        <div class="km-hero__copy-block {{ $key === 0 ? 'is-active' : '' }}" data-hero-copy="{{ $key }}">
                            <h1 class="km-hero__title">{{ $slide->title }}</h1>
                            @if ($slide->desc)
                                <p class="km-hero__desc">{{ $slide->desc }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <h1 class="km-hero__title">{{ __('Belajar, bertumbuh, dan berprestasi bersama kami.') }}</h1>
                    <p class="km-hero__desc">{{ __('Sekolah menengah atas negeri di Kabupaten Pulau Taliabu, Maluku Utara.') }}</p>
                @endif

                <p class="km-hero__type" aria-hidden="true">
                    <span class="km-hero__typed" data-hero-typing></span><span class="km-hero__caret"></span>
                </p>
                <span class="km-hero__sr">{{ $heroSlogan }}</span>

                <div class="km-hero__actions">
                    <a class="km-hero__btn km-hero__btn--primary" href="{{ route('profile.sekolah') }}">
                        <i class="fa fa-user-o" aria-hidden="true"></i>{{ __('Jelajahi Profil') }}
                    </a>
                    <a class="km-hero__btn km-hero__btn--ghost" href="{{ route('berita') }}">
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i>{{ __('Berita Sekolah') }}
                    </a>
                </div>
            </div>

            <div class="km-hero__media-wrap">
                <div class="km-hero__media">
                    <div class="km-hero__frame">
                        @if ($heroSlides->isNotEmpty())
                            @foreach ($heroSlides as $key => $slide)
                                <img class="km-hero__slide {{ $key === 0 ? 'is-active' : '' }}"
                                     src="{{ asset('storage/images/slider/' . $slide->image) }}"
                                     alt="{{ $slide->title ?: __('Suasana SMA Negeri 11 Pulau Taliabu') }}"
                                     data-hero-slide="{{ $key }}"
                                     loading="{{ $key === 0 ? 'eager' : 'lazy' }}">
                            @endforeach
                        @elseif (@$footer->logo)
                            <img class="km-hero__slide is-active"
                                 src="{{ asset('storage/images/logo/' . $footer->logo) }}"
                                 alt="Logo SMA Negeri 11 Pulau Taliabu">
                        @else
                            <img class="km-hero__slide is-active"
                                 src="{{ asset('Assets/Frontend/img/favicon.png') }}"
                                 alt="Logo SMA Negeri 11 Pulau Taliabu">
                        @endif
                    </div>
                </div>

                @if ($heroSlides->count() > 1)
                    <div class="km-hero__dots" role="tablist" aria-label="{{ __('Pilih gambar') }}">
                        @foreach ($heroSlides as $key => $slide)
                            <button type="button"
                                    class="km-hero__dot {{ $key === 0 ? 'is-active' : '' }}"
                                    data-hero-dot="{{ $key }}"
                                    role="tab"
                                    aria-selected="{{ $key === 0 ? 'true' : 'false' }}"
                                    aria-label="{{ __('Tampilkan gambar :n', ['n' => $key + 1]) }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="km-slider-welcome">
    <div class="container">
        <div class="km-slider-welcome__band km-marquee" aria-live="polite">
            <div class="km-slider-welcome__track km-marquee-track">
                <span>{{ __('Selamat datang di SMA Negeri 11 Pulau Taliabu') }}</span>
                <span>{{ __('Semangat belajar, berkarya, dan berprestasi bersama kami') }}</span>
                <span>{{ __('Selamat datang di SMA Negeri 11 Pulau Taliabu') }}</span>
                <span>{{ __('Semangat belajar, berkarya, dan berprestasi bersama kami') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Ujung bawah widget dipudarkan agar menyatu dengan bagian berikutnya. --}}
<div class="km-hero-fade" aria-hidden="true"></div>
