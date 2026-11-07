<div class="news-event-area">
    <div class="container">
        {{-- Berita --}}
        <section class="km-news-section" aria-labelledby="km-news-home-title">
            <header class="km-news-head">
                <p class="km-news-head__eyebrow">{{ __('SMA Negeri 11 Pulau Taliabu') }}</p>
                <h2 class="km-news-head__title" id="km-news-home-title">{{ __('Berita Sekolah') }}</h2>
                <span class="km-news-head__rule" aria-hidden="true"></span>
                <p class="km-news-head__sub">{{ __('Berita Pilihan') }}</p>
            </header>

            @if ($berita->isEmpty())
                <div class="km-news-empty">
                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    <strong>{{ __('Belum ada berita') }}</strong>
                    <span>{{ __('Berita yang dipublikasikan akan muncul di sini.') }}</span>
                </div>
            @else
                <div class="km-news-grid">
                    @foreach ($berita->take(3) as $beritas)
                        @include('frontend.content.partials.news-card', [
                            'item' => $beritas,
                            'variant' => 'home',
                        ])
                    @endforeach
                </div>

                <div class="km-news-more">
                    <a href="{{ route('berita') }}" class="km-news-more__btn">
                        {{ __('Lihat Berita Lainnya') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            @endif
        </section>

        {{-- Acara --}}
        <section class="km-news-events" aria-labelledby="km-news-events-title">
            <header class="km-news-head">
                <p class="km-news-head__eyebrow">{{ __('Agenda sekolah') }}</p>
                <h2 class="km-news-head__title" id="km-news-events-title">{{ __('Acara Sekolah') }}</h2>
                <span class="km-news-head__rule" aria-hidden="true"></span>
                <p class="km-news-head__sub">{{ __('Kegiatan yang akan datang') }}</p>
            </header>

            @if ($event->isEmpty())
                <div class="km-news-empty">
                    <i class="fa fa-calendar-o" aria-hidden="true"></i>
                    <strong>{{ __('Belum ada acara') }}</strong>
                    <span>{{ __('Agenda sekolah akan tampil di sini.') }}</span>
                </div>
            @else
                <div class="km-cards-grid">
                    @foreach ($event->take(3) as $eventItem)
                        @include('frontend.content.partials.event-card', ['eventItem' => $eventItem])
                    @endforeach
                </div>

                <div class="km-cards-more">
                    <a href="{{ route('event') }}" class="km-news-more__btn">
                        {{ __('Lihat Semua Acara') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            @endif
        </section>

        {{-- Publikasi --}}
        @if ($videos->isNotEmpty())
            <section class="km-news-events" aria-labelledby="km-news-publication-title">
                <header class="km-news-head">
                    <p class="km-news-head__eyebrow">{{ __('Dokumentasi') }}</p>
                    <h2 class="km-news-head__title" id="km-news-publication-title">{{ __('Publikasi') }}</h2>
                    <span class="km-news-head__rule" aria-hidden="true"></span>
                    <p class="km-news-head__sub">{{ __('Video kegiatan dan aktivitas sekolah') }}</p>
                </header>

                <div class="km-cards-grid">
                    @foreach ($videos->take(3) as $videoItem)
                        @include('frontend.content.partials.publication-card', ['videoItem' => $videoItem])
                    @endforeach
                </div>

                <div class="km-cards-more">
                    <a href="{{ route('publikasi') }}" class="km-news-more__btn">
                        {{ __('Lihat publikasi lainnya') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </section>
        @endif

        <section class="km-visit-panel" aria-labelledby="km-public-visits-title">
            <div class="km-visit-panel__heading">
                <div>
                    <span class="km-visit-panel__eyebrow"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ __('AKSES PUBLIK') }}</span>
                    <h2 id="km-public-visits-title">{{ __('Kunjungan situs') }}</h2>
                    <p>{{ __('Statistik dan tren kunjungan browser ke portal') }}</p>
                </div>
                <span class="km-visit-panel__privacy"><i class="fa fa-shield" aria-hidden="true"></i> {{ __('Data agregat anonim') }}</span>
            </div>

            <div class="km-visit-panel__body">
                <div class="km-visit-panel__overview">
                    <article class="km-visit-summary km-visit-summary--total">
                        <span class="km-visit-summary__icon"><i class="fa fa-globe" aria-hidden="true"></i></span>
                        <span class="km-visit-summary__label">{{ __('Total kunjungan') }}</span>
                        <strong data-visit-total="{{ $visitTotal }}">{{ number_format($visitTotal, 0, ',', '.') }}</strong>
                        <span class="km-visit-summary__note">{{ __('Satu sesi per hari') }}</span>
                    </article>
                    <article class="km-visit-summary km-visit-summary--week">
                        <span class="km-visit-summary__icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
                        <span class="km-visit-summary__label">{{ __('7 hari terakhir') }}</span>
                        <strong>{{ number_format($visitLastSevenDays, 0, ',', '.') }}</strong>
                        <span class="km-visit-summary__note">{{ __('Kunjungan portal publik') }}</span>
                    </article>
                </div>

                <div class="km-visit-panel__charts">
                    <div class="km-visit-chart-card">
                        <div class="km-visit-chart-card__heading">
                            <h3>{{ __('Tren kunjungan') }}</h3>
                            <span>{{ __('7 hari terakhir') }}</span>
                        </div>
                        <div class="km-visit-chart" role="img" aria-label="{{ __('Grafik kunjungan harian selama tujuh hari terakhir') }}">
                            @foreach ($visitTrend as $day)
                                @php
                                    $barHeight = $day['visits'] > 0 ? max(8, (int) round(($day['visits'] / $maxDailyVisits) * 100)) : 4;
                                @endphp
                                <div class="km-visit-chart__day" aria-label="{{ $day['date'] }}: {{ number_format($day['visits'], 0, ',', '.') }} kunjungan">
                                    <strong>{{ number_format($day['visits'], 0, ',', '.') }}</strong>
                                    <span class="km-visit-chart__track">
                                        <span class="km-visit-chart__bar" style="height: {{ $barHeight }}%"></span>
                                    </span>
                                    <span class="km-visit-chart__label">{{ $day['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="km-visit-browser-card">
                        <div class="km-visit-chart-card__heading">
                            <h3>{{ __('Browser pengunjung') }}</h3>
                            <span>{{ __('Semua waktu') }}</span>
                        </div>
                        @if ($browserVisits->isEmpty())
                            <div class="km-visit-browser-empty">{{ __('Data browser akan tampil setelah ada kunjungan.') }}</div>
                        @else
                            <ul class="km-visit-browser-list">
                                @foreach ($browserVisits as $browsers)
                                    <li>
                                        <span class="km-visit-browser-list__name"><i class="fa fa-circle" aria-hidden="true"></i>{{ $browsers['name'] }}</span>
                                        <span class="km-visit-browser-list__count">{{ number_format($browsers['visits'], 0, ',', '.') }} <small>({{ $browsers['share'] }}%)</small></span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('frontend.content.partials.news-modal')
</div>
