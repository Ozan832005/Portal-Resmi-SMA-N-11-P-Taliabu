@php
    $acaraMendatang = $acara ?? null;
    $tanggalAcara = $acaraMendatang && $acaraMendatang->acara
        ? \Carbon\Carbon::parse($acaraMendatang->acara)->locale('id')
        : null;
@endphp

<section class="km-panel">
    <div class="km-panel-head">
        <h2 class="km-panel-title">Agenda sekolah</h2>
        @isset($tautanAcara)
            <a class="km-link" href="{{ $tautanAcara }}">Lihat semua acara</a>
        @endisset
    </div>

    @if ($acaraMendatang)
        @if ($tanggalAcara)
            <div class="km-event-date">
                <span class="km-event-day">{{ $tanggalAcara->translatedFormat('j') }}</span>
                <span class="km-event-month">{{ $tanggalAcara->translatedFormat('F Y') }}</span>
            </div>
        @endif

        <h3 class="km-event-title">{{ $acaraMendatang->title }}</h3>

        <ul class="km-meta">
            @if ($tanggalAcara)
                <li>
                    <i data-feather="calendar"></i>
                    <span>{{ $tanggalAcara->translatedFormat('l, j F Y') }}</span>
                </li>
                <li>
                    <i data-feather="clock"></i>
                    <span>Mulai {{ $tanggalAcara->format('H.i') }}</span>
                </li>
            @endif
            <li>
                <i data-feather="map-pin"></i>
                <span>{{ $acaraMendatang->lokasi ?: 'Lokasi belum diisi' }}</span>
            </li>
        </ul>

        @if ($acaraMendatang->desc)
            <p class="km-event-desc">{{ \Illuminate\Support\Str::limit($acaraMendatang->desc, 180) }}</p>
        @endif
    @else
        <div class="km-empty">
            <p class="km-empty-title">Belum ada acara mendatang</p>
            <p class="km-empty-text">Acara yang ditambahkan lewat menu Acara sekolah akan tampil di sini.</p>
        </div>
    @endif
</section>
