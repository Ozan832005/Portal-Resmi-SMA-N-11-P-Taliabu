@extends('layouts.Frontend.app')

@section('title', __('Daftar Alumni'))

@section('content')
    @section('about')
        <main class="km-alumni-shell">
            <div class="container">
                <div class="km-widget-headline km-widget-headline--static km-alumni-headline">
                    <h1 class="km-widget-headline__title"><i class="fa fa-graduation-cap" aria-hidden="true"></i> {{ __('Daftar Alumni') }}</h1>
                    <p class="km-widget-headline__description">{{ __('Para lulusan yang terdaftar sebagai alumni SMA Negeri 11 Pulau Taliabu.') }}</p>
                </div>

                @if ($alumni->isEmpty())
                    <div class="km-empty">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <strong>{{ __('Data alumni belum tersedia') }}</strong>
                        <span>{{ __('Alumni aktif yang terdaftar akan tampil di halaman ini.') }}</span>
                    </div>
                @else
                    <div class="km-alumni-grid">
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
                                    <h2>{{ $alumnus->name }}</h2>
                                    <p>{{ __('Lulusan SMA Negeri 11 Pulau Taliabu') }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="km-publication-pagination">
                        {{ $alumni->links() }}
                    </div>

                    <div class="km-alumni-action">
                        <a href="{{ route('alumni') }}" class="km-btn km-btn--ghost">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('Kembali ke Alumni') }}
                        </a>
                    </div>
                @endif
            </div>
        </main>
    @endsection
@endsection
