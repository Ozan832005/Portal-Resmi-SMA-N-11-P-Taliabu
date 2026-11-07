@php
    /**
     * Kartu berita. Dipakai di beranda ($variant = 'home') dan halaman Berita
     * ($variant = 'grid'). Tombol "Baca Berita" membuka modal; judul & gambar
     * tetap menuju halaman detail berita.
     *
     * @var \App\Models\Berita $item
     */
    $variant = $variant ?? 'home';
    $newsDate = \Carbon\Carbon::parse($item->created_at);
    $newsAuthor = optional($item->user)->name ?: __('Redaksi');
    $newsCategory = trim((string) optional($item->kategori)->nama);
    $newsViews = (int) ($item->views ?? 0);
    $newsImage = $item->thumbnail ? asset('storage/images/berita/' . $item->thumbnail) : '';
    $newsUrl = route('detail.berita', $item->slug);
    $newsPlain = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $item->content)));
    $newsWords = $newsPlain === '' ? 0 : count(preg_split('/\s+/u', $newsPlain));
    $newsReadMinutes = max(1, (int) ceil($newsWords / 200));
@endphp

<article class="km-news-card km-news-card--{{ $variant }}">
    <a href="{{ $newsUrl }}" class="km-news-card__media" tabindex="-1" aria-hidden="true">
        @if ($newsImage !== '')
            <img src="{{ $newsImage }}" alt="" loading="lazy">
        @else
            <span class="km-news-card__media-empty"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
        @endif

        @if ($variant === 'home')
            <span class="km-news-card__chips">
                @if ($newsCategory !== '')
                    <span class="km-news-card__cat">{{ $newsCategory }}</span>
                @endif
                <span class="km-news-card__views"><i class="fa fa-eye" aria-hidden="true"></i>{{ number_format($newsViews) }}</span>
            </span>
        @endif
    </a>

    <div class="km-news-card__body">
        <div class="km-news-card__meta">
            <span><i class="fa fa-calendar-o" aria-hidden="true"></i>{{ $newsDate->translatedFormat('d M Y') }}</span>
            <span class="km-news-card__dot" aria-hidden="true"></span>
            <span class="km-news-card__author">{{ $newsAuthor }}</span>
            @if ($variant === 'grid')
                <span class="km-news-card__meta-right"><i class="fa fa-eye" aria-hidden="true"></i>{{ number_format($newsViews) }}</span>
            @endif
        </div>

        <h3 class="km-news-card__title">
            <a href="{{ $newsUrl }}">{{ $item->title }}</a>
        </h3>

        @if ($newsPlain !== '')
            <p class="km-news-card__excerpt">{{ \Illuminate\Support\Str::limit($newsPlain, 150) }}</p>
        @endif

        <button type="button"
                class="km-news-card__btn"
                data-news-open
                data-news-title="{{ $item->title }}"
                data-news-cat="{{ $newsCategory }}"
                data-news-author="{{ $newsAuthor }}"
                data-news-date="{{ $newsDate->translatedFormat('d F Y') }}"
                data-news-views="{{ number_format($newsViews) }}"
                data-news-read="{{ __(':count menit baca', ['count' => $newsReadMinutes]) }}"
                data-news-image="{{ $newsImage }}"
                data-news-url="{{ $newsUrl }}">
            {{ __('Baca Berita') }}
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
        </button>
    </div>

    <template data-news-content>{!! $item->content !!}</template>
</article>
