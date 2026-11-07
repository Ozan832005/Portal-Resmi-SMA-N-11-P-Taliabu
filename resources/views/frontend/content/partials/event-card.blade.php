@php
    /**
     * Kartu acara. Dipakai di beranda dan halaman Acara.
     *
     * @var \App\Models\Events $eventItem
     */
    $eventDate = \Carbon\Carbon::parse($eventItem->acara);
    $eventViews = (int) ($eventItem->views ?? 0);
    $eventUrl = route('detail.event', $eventItem->slug);
    $eventThumb = $eventItem->thumbnail ? asset('storage/images/event/' . $eventItem->thumbnail) : '';
    $eventDesc = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $eventItem->desc)));
@endphp

<article class="km-event-tile">
    <a href="{{ $eventUrl }}" class="km-event-tile__media" tabindex="-1" aria-hidden="true">
        @if ($eventThumb !== '')
            <img src="{{ $eventThumb }}" alt="" loading="lazy">
        @else
            <span class="km-event-tile__fallback"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
        @endif

        <span class="km-event-tile__chips">
            <span class="km-event-tile__date">
                <strong>{{ $eventDate->format('d') }}</strong>
                <span>{{ $eventDate->translatedFormat('M Y') }}</span>
            </span>
            <span class="km-event-tile__views"><i class="fa fa-eye" aria-hidden="true"></i>{{ number_format($eventViews) }}</span>
        </span>
    </a>

    <div class="km-event-tile__body">
        <div class="km-event-tile__meta">
            <span><i class="fa fa-clock-o" aria-hidden="true"></i>{{ $eventDate->format('H:i') }} WIT</span>
            @if ($eventItem->lokasi)
                <span><i class="fa fa-map-marker" aria-hidden="true"></i>{{ \Illuminate\Support\Str::limit($eventItem->lokasi, 34) }}</span>
            @endif
        </div>

        <h3 class="km-event-tile__title">
            <a href="{{ $eventUrl }}">{{ $eventItem->title }}</a>
        </h3>

        @if ($eventDesc !== '')
            <p class="km-event-tile__desc">{{ \Illuminate\Support\Str::limit($eventDesc, 140) }}</p>
        @endif

        <a href="{{ $eventUrl }}" class="km-event-tile__btn">
            {{ __('Detail Acara') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
