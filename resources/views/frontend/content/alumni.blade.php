@extends('layouts.Frontend.app')

@section('title', __('Alumni'))

@section('content')
    @section('about')
        <main class="km-alumni-shell">
            <div class="container">
                <div class="km-widget-headline km-widget-headline--static km-alumni-headline">
                    <h1 class="km-widget-headline__title"><i class="fa fa-graduation-cap" aria-hidden="true"></i> {{ __('Alumni SMA 11') }}</h1>
                    <p class="km-widget-headline__description">{{ __('Cerita dan perjalanan para lulusan yang menjadi bagian dari keluarga besar SMA Negeri 11 Pulau Taliabu.') }}</p>
                </div>

                @if ($alumni->isEmpty())
                    <div class="km-empty">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <strong>{{ __('Data alumni belum tersedia') }}</strong>
                        <span>{{ __('Alumni aktif yang terdaftar akan tampil di halaman ini.') }}</span>
                    </div>
                @else
                    <section class="km-alumni-section" aria-label="{{ __('Alumni sekolah') }}">
                        <div class="km-alumni-section__heading">
                            <div>
                                <span class="km-eyebrow"><i class="fa fa-star" aria-hidden="true"></i> {{ __('Keluarga besar sekolah') }}</span>
                                <h2>{{ __('Kenali alumni kami') }}</h2>
                                <p>{{ __('Geser kartu untuk melihat lebih banyak alumni.') }}</p>
                            </div>
                            <div class="km-alumni-controls" aria-label="{{ __('Kontrol kartu alumni') }}">
                                <button type="button" class="km-alumni-control" data-alumni-scroll="-1" aria-label="{{ __('Geser alumni ke kiri') }}">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="km-alumni-control" data-alumni-scroll="1" aria-label="{{ __('Geser alumni ke kanan') }}">
                                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <div class="km-alumni-track" id="alumni-track">
                            @foreach ($alumni as $alumnus)
                                <article class="km-alumni-card">
                                    <div class="km-alumni-card__portrait">
                                        @if ($alumnus->foto_profile)
                                            <img src="{{ asset('storage/images/profile/' . $alumnus->foto_profile) }}" alt="{{ __('Foto') }} {{ $alumnus->name }}" loading="lazy">
                                        @else
                                            <div class="km-alumni-card__placeholder" aria-label="{{ __('Foto alumni belum tersedia') }}">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                        <span class="km-alumni-card__badge"><i class="fa fa-graduation-cap" aria-hidden="true"></i> {{ __('Alumni') }}</span>
                                    </div>
                                    <div class="km-alumni-card__body">
                                        <h3>{{ $alumnus->name }}</h3>
                                        <p>{{ __('Lulusan SMA Negeri 11 Pulau Taliabu') }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="km-alumni-action">
                            <a href="{{ route('alumni.semua') }}" class="km-btn km-btn--primary">
                                {{ __('Lihat alumni lainnya') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </section>
                @endif
            </div>
        </main>
    @endsection
@endsection
