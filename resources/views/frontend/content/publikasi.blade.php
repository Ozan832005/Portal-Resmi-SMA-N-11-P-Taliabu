@extends('layouts.Frontend.app')

@section('title', __('Publikasi'))

@section('content')
    @section('about')
        <div class="km-publication-shell km-cards-page">
            <div class="container">
                <nav class="km-news-crumbs" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> {{ __('Beranda') }}</a>
                    <span class="km-news-crumbs__sep" aria-hidden="true">/</span>
                    <span class="km-news-crumbs__current">{{ __('Publikasi') }}</span>
                </nav>

                <header class="km-news-head">
                    <p class="km-news-head__eyebrow">{{ __('Dokumentasi') }}</p>
                    <h1 class="km-news-head__title">{{ __('Publikasi') }}</h1>
                    <span class="km-news-head__rule" aria-hidden="true"></span>
                    <p class="km-news-head__sub">{{ __('Video kegiatan dan aktivitas sekolah') }}</p>
                </header>

                @if ($videos->isEmpty())
                    <div class="km-news-empty">
                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                        <strong>{{ __('Belum ada publikasi') }}</strong>
                        <span>{{ __('Video yang dipublikasikan akan tampil di sini.') }}</span>
                    </div>
                @else
                    <div class="km-cards-grid">
                        @foreach ($videos as $videoItem)
                            @include('frontend.content.partials.publication-card', ['videoItem' => $videoItem])
                        @endforeach
                    </div>

                    <div class="km-video-pagination">
                        {{ $videos->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endsection
@endsection
