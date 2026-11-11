@extends('layouts.Frontend.app')

@section('title')
    {{ $berita->title }}
@endsection

@section('social_meta')
    @php
        $socialDescription = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $berita->content)));
        $socialDescription = $socialDescription !== '' ? \Illuminate\Support\Str::limit($socialDescription, 200) : $berita->title;
        $socialImage = $berita->thumbnail ? secure_asset('storage/images/berita/' . $berita->thumbnail) : null;
        $socialUrl = url()->current();
    @endphp
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $berita->title }}">
    <meta property="og:description" content="{{ $socialDescription }}">
    <meta property="og:url" content="{{ $socialUrl }}">
    @if ($socialImage)
        <meta property="og:image" content="{{ $socialImage }}">
        <meta property="og:image:alt" content="{{ $berita->title }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $socialImage }}">
    @endif
    <meta name="twitter:title" content="{{ $berita->title }}">
    <meta name="twitter:description" content="{{ $socialDescription }}">
@endsection

@section('content')
    @section('about')
        @php
            $articleDate = \Carbon\Carbon::parse($berita->created_at);
            $articleCategory = trim((string) optional($berita->kategori)->nama);
            $articleAuthor = optional($berita->user)->name ?: __('Redaksi');
            $articlePlain = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $berita->content)));
            $articleWords = $articlePlain === '' ? 0 : count(preg_split('/\s+/u', $articlePlain));
            $articleMinutes = max(1, (int) ceil($articleWords / 200));
            $articleUrl = url()->current();
            $relatedNews = $beritaOther->where('id', '!=', $berita->id)->take(3);
        @endphp

        <div class="news-details-page-area km-news-article-page">
            <div class="container">
                <nav class="km-news-crumbs" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> {{ __('Beranda') }}</a>
                    <span class="km-news-crumbs__sep" aria-hidden="true">/</span>
                    <a href="{{ route('berita') }}">{{ __('Berita') }}</a>
                    @if ($articleCategory !== '')
                        <span class="km-news-crumbs__sep" aria-hidden="true">/</span>
                        <a href="{{ route('berita', ['kategori' => $berita->kategori_id]) }}">{{ $articleCategory }}</a>
                    @endif
                </nav>

                <article class="km-news-article">
                    @if ($articleCategory !== '')
                        <span class="km-news-article__cat">{{ $articleCategory }}</span>
                    @endif

                    <h1 class="km-news-article__title">{{ $berita->title }}</h1>

                    <ul class="km-news-article__meta">
                        <li><i class="fa fa-user-o" aria-hidden="true"></i>{{ $articleAuthor }}</li>
                        <li><i class="fa fa-calendar-o" aria-hidden="true"></i>{{ $articleDate->translatedFormat('d F Y') }}</li>
                        <li><i class="fa fa-eye" aria-hidden="true"></i>{{ number_format((int) $readCount) }} {{ __('dilihat') }}</li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i>{{ __(':count menit baca', ['count' => $articleMinutes]) }}</li>
                    </ul>

                    <div class="km-news-share">
                        <span class="km-news-share__label">{{ __('Bagikan') }}</span>
                        <a class="km-news-share-btn km-news-share-btn--wa" href="https://wa.me/?text={{ urlencode($berita->title . ' ' . $articleUrl) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke WhatsApp') }}"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                        <a class="km-news-share-btn km-news-share-btn--x" href="https://twitter.com/intent/tweet?text={{ urlencode($berita->title) }}&amp;url={{ urlencode($articleUrl) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke X') }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a class="km-news-share-btn km-news-share-btn--fb" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($articleUrl) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke Facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a class="km-news-share-btn km-news-share-btn--tg" href="https://t.me/share/url?url={{ urlencode($articleUrl) }}&amp;text={{ urlencode($berita->title) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke Telegram') }}"><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
                        <button type="button"
                                class="km-news-share-btn km-news-share-btn--copy"
                                data-copy-link="{{ $articleUrl }}"
                                data-copy-label="{{ __('Salin tautan berita') }}"
                                data-copied-label="{{ __('Tautan disalin') }}"
                                aria-label="{{ __('Salin tautan berita') }}">
                            <i class="fa fa-link" aria-hidden="true"></i>
                        </button>
                    </div>

                    @if ($berita->thumbnail)
                        <figure class="km-news-article__media">
                            <img src="{{ asset('storage/images/berita/' . $berita->thumbnail) }}" alt="{{ $berita->title }}">
                        </figure>
                    @endif

                    <div class="km-news-prose">
                        {!! $berita->content !!}
                    </div>

                    <div class="km-news-article__foot">
                        <a href="{{ route('berita') }}" class="km-news-article__back">
                            <i class="fa fa-angle-left" aria-hidden="true"></i> {{ __('Semua berita') }}
                        </a>
                        <span class="km-news-article__foot-note">
                            {{ __('Terbit :date', ['date' => $articleDate->translatedFormat('d F Y')]) }}
                        </span>
                    </div>
                </article>

                @if ($relatedNews->isNotEmpty())
                    <section class="km-news-related" aria-labelledby="km-news-related-title">
                        <div class="km-news-related__head">
                            <h2 class="km-news-related__title" id="km-news-related-title">{{ __('Berita lainnya') }}</h2>
                            <span class="km-news-related__line" aria-hidden="true"></span>
                        </div>

                        <div class="km-news-grid">
                            @foreach ($relatedNews as $beritas)
                                @include('frontend.content.partials.news-card', [
                                    'item' => $beritas,
                                    'variant' => 'grid',
                                ])
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            @include('frontend.content.partials.news-modal')
        </div>
    @endsection
@endsection
