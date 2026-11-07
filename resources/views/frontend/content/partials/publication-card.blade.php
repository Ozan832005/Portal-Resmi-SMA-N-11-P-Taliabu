@php
    /**
     * Kartu publikasi (video). Kelas .km-publication-media dan
     * .km-publication-play-button dipertahankan karena dipakai skrip pemutar
     * video di layouts/Frontend/scripts.blade.php.
     *
     * @var \App\Models\Video $videoItem
     */
    $playback = $videoItem->playback();
    $videoTypeLabel = $playback && $playback['type'] === 'youtube'
        ? __('YouTube')
        : ($playback && $playback['type'] === 'file' ? __('Video sekolah') : __('Publikasi'));
@endphp

<article class="km-video-card">
    <div class="km-publication-media">
        @if ($playback && $playback['type'] === 'youtube')
            @php
                $videoId = basename(parse_url($playback['url'], PHP_URL_PATH) ?: '');
            @endphp
            <img
                class="km-publication-thumbnail"
                src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                alt=""
                loading="lazy">
            <button
                class="km-publication-play-button"
                type="button"
                data-embed-url="{{ $playback['url'] }}"
                data-video-title="{{ $videoItem->title }}"
                aria-label="{{ __('Putar video di situs') }}: {{ $videoItem->title }}">
                <i class="fa fa-youtube-play" aria-hidden="true"></i>
                <span>{{ __('Putar video') }}</span>
            </button>
        @elseif ($playback && $playback['type'] === 'file')
            @php
                $extension = strtolower(pathinfo(parse_url($playback['url'], PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                $mimeType = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'][$extension];
            @endphp
            <video controls preload="metadata" playsinline>
                <source src="{{ $playback['url'] }}" type="{{ $mimeType }}">
                {{ __('Browser Anda tidak mendukung pemutaran video.') }}
            </video>
        @elseif ($playback)
            <div class="km-publication-unavailable">
                <a class="km-publication-link" href="{{ $videoItem->url }}" target="_blank" rel="noopener" aria-label="{{ __('Tonton video') }}: {{ $videoItem->title }}">
                    <i class="fa fa-play-circle" aria-hidden="true"></i>
                    <span>{{ __('Tonton video') }}</span>
                </a>
            </div>
        @else
            <div class="km-publication-unavailable">
                <i class="fa fa-video-camera" aria-hidden="true"></i>
                <span>{{ __('Tautan video tidak tersedia') }}</span>
            </div>
        @endif
    </div>

    @if ($playback && $playback['type'] === 'youtube')
        <a class="km-publication-fallback-link" href="{{ $videoItem->url }}" target="_blank" rel="noopener">
            <i class="fa fa-external-link" aria-hidden="true"></i> {{ __('Pemutar tidak tersedia? Buka di YouTube') }}
        </a>
    @endif

    <div class="km-video-card__body">
        <div class="km-video-card__meta">
            <span><i class="fa fa-calendar-o" aria-hidden="true"></i>{{ \Carbon\Carbon::parse($videoItem->created_at)->translatedFormat('d M Y') }}</span>
            <span><i class="fa fa-play-circle" aria-hidden="true"></i>{{ $videoTypeLabel }}</span>
        </div>

        <h3 class="km-video-card__title">{{ $videoItem->title }}</h3>

        @if ($videoItem->desc)
            <p class="km-video-card__desc">{{ \Illuminate\Support\Str::limit($videoItem->desc, 150) }}</p>
        @endif

        @if ($playback && $playback['type'] === 'youtube')
            <button type="button" class="km-video-card__btn" data-video-trigger>
                {{ __('Putar video') }} <i class="fa fa-play" aria-hidden="true"></i>
            </button>
        @elseif ($videoItem->url)
            <a class="km-video-card__btn" href="{{ $videoItem->url }}" target="_blank" rel="noopener">
                {{ __('Buka video') }} <i class="fa fa-external-link" aria-hidden="true"></i>
            </a>
        @endif
    </div>
</article>
