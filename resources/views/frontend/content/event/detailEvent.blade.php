@extends('layouts.Frontend.app')

@section('title')
    {{ $event->title }}
@endsection

@section('content')
    @section('about')
        @php
            $eventDate = \Carbon\Carbon::parse($event->acara);
            $eventUrl = url()->current();
            $eventBody = collect([
                $event->desc,
                $event->content !== $event->desc ? $event->content : null,
            ])->filter(fn ($part) => is_string($part) && trim($part) !== '');
            $relatedEvents = $eventOther->where('id', '!=', $event->id)->take(3);
        @endphp

        <div class="news-details-page-area km-news-article-page">
            <div class="container">
                <nav class="km-news-crumbs" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> {{ __('Beranda') }}</a>
                    <span class="km-news-crumbs__sep" aria-hidden="true">/</span>
                    <a href="{{ route('event') }}">{{ __('Acara') }}</a>
                </nav>

                <article class="km-news-article">
                    <span class="km-news-article__cat">{{ __('Agenda sekolah') }}</span>

                    <h1 class="km-news-article__title">{{ $event->title }}</h1>

                    <ul class="km-news-article__meta">
                        <li><i class="fa fa-calendar-o" aria-hidden="true"></i>{{ $eventDate->translatedFormat('d F Y') }}</li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i>{{ $eventDate->format('H:i') }} WIT</li>
                        @if ($event->lokasi)
                            <li><i class="fa fa-map-marker" aria-hidden="true"></i>{{ $event->lokasi }}</li>
                        @endif
                        <li><i class="fa fa-eye" aria-hidden="true"></i>{{ number_format((int) $readCount) }} {{ __('dilihat') }}</li>
                    </ul>

                    <div class="km-news-share">
                        <span class="km-news-share__label">{{ __('Bagikan') }}</span>
                        <a class="km-news-share-btn km-news-share-btn--wa" href="https://wa.me/?text={{ urlencode($event->title . ' ' . $eventUrl) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke WhatsApp') }}"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                        <a class="km-news-share-btn km-news-share-btn--x" href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&amp;url={{ urlencode($eventUrl) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke X') }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a class="km-news-share-btn km-news-share-btn--fb" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($eventUrl) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke Facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a class="km-news-share-btn km-news-share-btn--tg" href="https://t.me/share/url?url={{ urlencode($eventUrl) }}&amp;text={{ urlencode($event->title) }}" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke Telegram') }}"><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
                        <button type="button"
                                class="km-news-share-btn km-news-share-btn--copy"
                                data-copy-link="{{ $eventUrl }}"
                                data-copy-label="{{ __('Salin tautan acara') }}"
                                data-copied-label="{{ __('Tautan disalin') }}"
                                aria-label="{{ __('Salin tautan acara') }}">
                            <i class="fa fa-link" aria-hidden="true"></i>
                        </button>
                    </div>

                    @if ($event->thumbnail)
                        <figure class="km-news-article__media">
                            <img src="{{ asset('storage/images/event/' . $event->thumbnail) }}" alt="{{ $event->title }}">
                        </figure>
                    @endif

                    <ul class="km-event-facts">
                        <li>
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                            <div>
                                <span class="km-event-facts__label">{{ __('Tanggal') }}</span>
                                <span class="km-event-facts__value">{{ $eventDate->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            <div>
                                <span class="km-event-facts__label">{{ __('Waktu') }}</span>
                                <span class="km-event-facts__value">{{ $eventDate->format('H:i') }} WIT</span>
                            </div>
                        </li>
                        @if ($event->lokasi)
                            <li>
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <div>
                                    <span class="km-event-facts__label">{{ __('Lokasi') }}</span>
                                    <span class="km-event-facts__value">{{ $event->lokasi }}</span>
                                </div>
                            </li>
                        @endif
                    </ul>

                    @if ($eventBody->isNotEmpty())
                        <div class="km-news-prose">
                            @foreach ($eventBody as $paragraph)
                                <p>{!! nl2br(e($paragraph)) !!}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="km-news-article__foot">
                        <a href="{{ route('event') }}" class="km-news-article__back">
                            <i class="fa fa-angle-left" aria-hidden="true"></i> {{ __('Semua acara') }}
                        </a>
                        <span class="km-news-article__foot-note">
                            {{ __('Agenda :date', ['date' => $eventDate->translatedFormat('d F Y')]) }}
                        </span>
                    </div>
                </article>

                @if ($relatedEvents->isNotEmpty())
                    <section class="km-news-related" aria-labelledby="km-event-related-title">
                        <div class="km-news-related__head">
                            <h2 class="km-news-related__title" id="km-event-related-title">{{ __('Acara lainnya') }}</h2>
                            <span class="km-news-related__line" aria-hidden="true"></span>
                        </div>

                        <div class="km-cards-grid">
                            @foreach ($relatedEvents as $eventItem)
                                @include('frontend.content.partials.event-card', ['eventItem' => $eventItem])
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    @endsection
@endsection
