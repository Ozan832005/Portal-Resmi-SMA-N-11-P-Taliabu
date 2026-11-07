@php
    /**
     * Poster memakai thumbnail video (bila YouTube) agar banner menampilkan
     * isi video yang sebenarnya; foto banner tetap menjadi cadangan.
     */
    $videoPlayback = $video ? $video->playback() : null;
    $videoPoster = asset('Assets/Frontend/img/banner/1.jpg');
    $videoKind = __('Video sekolah');

    if ($videoPlayback && $videoPlayback['type'] === 'youtube') {
        $videoKind = __('YouTube');
        $youtubeId = basename(parse_url($videoPlayback['url'], PHP_URL_PATH) ?: '');

        if ($youtubeId !== '') {
            $videoPoster = 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg';
        }
    }
@endphp

<div class="video-area km-video-stage" style="--km-video-poster: url('{{ $videoPoster }}');">
    @if ($video != NULL)
        <div class="container">
            <div class="km-video-stage__grid">
                <div class="km-video-stage__copy">
                    <h2 class="km-video-stage__title">{{ $video->title }}</h2>

                    @if ($video->desc)
                        <p class="km-video-stage__desc">{{ $video->desc }}</p>
                    @endif

                    <div class="km-video-stage__actions">
                        <a class="play-btn popup-youtube km-video-stage__play" href="{{ $video->url }}" aria-label="{{ __('Putar video') }}: {{ $video->title }}">
                            <i class="fa fa-play" aria-hidden="true"></i>
                            <span>{{ __('Putar video') }}</span>
                        </a>
                        <span class="km-video-stage__meta">
                            <i class="fa fa-youtube-play" aria-hidden="true"></i>{{ $videoKind }}
                        </span>
                    </div>
                </div>

                <a class="km-video-stage__poster popup-youtube" href="{{ $video->url }}" aria-label="{{ __('Putar video') }}: {{ $video->title }}">
                    <img src="{{ $videoPoster }}" alt="" loading="lazy">
                    <span class="km-video-stage__key" aria-hidden="true"><i class="fa fa-play"></i></span>
                </a>
            </div>
        </div>
    @endif
</div>
