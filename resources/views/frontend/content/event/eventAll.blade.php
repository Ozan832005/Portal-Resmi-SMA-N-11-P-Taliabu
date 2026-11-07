@extends('layouts.Frontend.app')
@section('title')
    {{ __('Acara Sekolah') }}
@endsection

@section('content')
    @section('about')
        <div class="event-page-area km-cards-page">
            <div class="container">
                <nav class="km-news-crumbs" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> {{ __('Beranda') }}</a>
                    <span class="km-news-crumbs__sep" aria-hidden="true">/</span>
                    <span class="km-news-crumbs__current">{{ __('Acara sekolah') }}</span>
                </nav>

                <header class="km-news-head">
                    <p class="km-news-head__eyebrow">{{ __('Agenda sekolah') }}</p>
                    <h1 class="km-news-head__title">{{ __('Acara Sekolah') }}</h1>
                    <span class="km-news-head__rule" aria-hidden="true"></span>
                    <p class="km-news-head__sub">{{ __('Kegiatan dan agenda SMA Negeri 11 Pulau Taliabu') }}</p>
                </header>

                @if ($event->isEmpty())
                    <div class="km-news-empty">
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        <strong>{{ __('Belum ada acara') }}</strong>
                        <span>{{ __('Agenda sekolah akan tampil di sini.') }}</span>
                    </div>
                @else
                    <div class="km-cards-grid">
                        @foreach ($event as $eventItem)
                            @include('frontend.content.partials.event-card', ['eventItem' => $eventItem])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endsection
@endsection
