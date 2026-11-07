@php
    /**
     * Hero berita halaman Berita: sorotan berita terbaru dengan panah & titik.
     *
     * @var \Illuminate\Support\Collection<int, \App\Models\Berita> $items
     */
    $items = $items ?? collect();
@endphp

@if ($items->isNotEmpty())
    <section class="km-news-feature" data-news-feature aria-label="{{ __('Sorotan berita') }}">
        @foreach ($items as $index => $highlight)
            @php
                $highlightDate = \Carbon\Carbon::parse($highlight->created_at);
                $highlightCategory = trim((string) optional($highlight->kategori)->nama);
                $highlightPlain = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $highlight->content)));
            @endphp

            <article class="km-news-feature__slide {{ $index === 0 ? 'is-active' : '' }}"
                     data-news-feature-slide="{{ $index }}"
                     aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                @if ($highlight->thumbnail)
                    <img class="km-news-feature__img"
                         src="{{ asset('storage/images/berita/' . $highlight->thumbnail) }}"
                         alt="{{ $highlight->title }}"
                         loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                @endif

                <div class="km-news-feature__content">
                    <div class="container">
                        <div class="km-news-feature__meta">
                            <span><i class="fa fa-calendar-o" aria-hidden="true"></i>{{ $highlightDate->translatedFormat('d M Y') }}</span>
                            @if ($highlightCategory !== '')
                                <span><i class="fa fa-tag" aria-hidden="true"></i>{{ $highlightCategory }}</span>
                            @endif
                            <span><i class="fa fa-eye" aria-hidden="true"></i>{{ __(':count dilihat', ['count' => number_format((int) ($highlight->views ?? 0))]) }}</span>
                        </div>

                        <h2 class="km-news-feature__title">{{ $highlight->title }}</h2>

                        @if ($highlightPlain !== '')
                            <p class="km-news-feature__excerpt">{{ \Illuminate\Support\Str::limit($highlightPlain, 180) }}</p>
                        @endif

                        <a class="km-news-feature__btn" href="{{ route('detail.berita', $highlight->slug) }}">
                            {{ __('Baca Selengkapnya') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach

        @if ($items->count() > 1)
            <button type="button" class="km-news-feature__nav km-news-feature__nav--prev" data-news-feature-prev aria-label="{{ __('Berita sebelumnya') }}">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="km-news-feature__nav km-news-feature__nav--next" data-news-feature-next aria-label="{{ __('Berita berikutnya') }}">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </button>

            <div class="km-news-feature__dots" role="tablist" aria-label="{{ __('Pilih sorotan berita') }}">
                @foreach ($items as $index => $highlight)
                    <button type="button"
                            class="km-news-feature__dot {{ $index === 0 ? 'is-active' : '' }}"
                            data-news-feature-dot="{{ $index }}"
                            role="tab"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-label="{{ __('Tampilkan sorotan :n', ['n' => $index + 1]) }}"></button>
                @endforeach
            </div>
        @endif
    </section>
@endif
