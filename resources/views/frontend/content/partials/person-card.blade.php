@php
    /**
     * Kartu pengajar. Dipakai di carousel beranda.
     *
     * @var \App\Models\User $pengajars
     */
    $detailPengajar = $pengajars->userDetail;
    $personSubject = trim((string) optional($detailPengajar)->mengajar) ?: __('Mata pelajaran');
    $personNip = optional($detailPengajar)->nip;
    $personSocials = [];

    if (optional($detailPengajar)->website) {
        $personSocials[] = ['url' => $detailPengajar->website, 'icon' => 'fa-globe', 'label' => 'Website'];
    }

    if ($pengajars->email) {
        $personSocials[] = ['url' => 'mailto:' . $pengajars->email, 'icon' => 'fa-envelope-o', 'label' => 'Email'];
    }

    if (optional($detailPengajar)->linkedln) {
        $personSocials[] = ['url' => 'https://www.linkedin.com/in/' . $detailPengajar->linkedln, 'icon' => 'fa-linkedin', 'label' => 'LinkedIn'];
    }

    if (optional($detailPengajar)->twitter) {
        $personSocials[] = ['url' => 'https://www.twitter.com/' . $detailPengajar->twitter, 'icon' => 'fa-twitter', 'label' => 'Twitter'];
    }

    if (optional($detailPengajar)->facebook) {
        $personSocials[] = ['url' => 'https://www.facebook.com/' . $detailPengajar->facebook, 'icon' => 'fa-facebook', 'label' => 'Facebook'];
    }

    if (optional($detailPengajar)->instagram) {
        $personSocials[] = ['url' => 'https://www.instagram.com/' . $detailPengajar->instagram, 'icon' => 'fa-instagram', 'label' => 'Instagram'];
    }
@endphp

<article class="km-person-card">
    <div class="km-person-card__portrait">
        @if ($pengajars->foto_profile)
            <img src="{{ asset('storage/images/profile/' . $pengajars->foto_profile) }}"
                 alt="{{ __('Foto :name', ['name' => $pengajars->name]) }}"
                 loading="lazy">
        @else
            <div class="km-person-card__placeholder" role="img" aria-label="{{ __('Foto :name belum tersedia', ['name' => $pengajars->name]) }}">
                <i class="fa fa-user" aria-hidden="true"></i>
            </div>
        @endif

        <span class="km-person-card__chips">
            <span class="km-person-card__chip">{{ $personSubject }}</span>
            <span class="km-person-card__role"><i class="fa fa-graduation-cap" aria-hidden="true"></i>{{ __('Guru') }}</span>
        </span>

        @if ($personSocials !== [])
            <div class="km-person-card__contact" aria-label="{{ __('Kontak :name', ['name' => $pengajars->name]) }}">
                <span class="km-person-card__contact-curtain km-person-card__contact-curtain--left" aria-hidden="true"></span>
                <span class="km-person-card__contact-curtain km-person-card__contact-curtain--right" aria-hidden="true"></span>
                <ul class="km-person-card__social" aria-label="{{ __('Informasi kontak') }}">
                    @foreach ($personSocials as $social)
                        <li>
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }} {{ $pengajars->name }}">
                                <i class="fa {{ $social['icon'] }}" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="km-person-card__body">
        <span class="km-person-card__meta">
            @if ($personNip)
                <span><i class="fa fa-id-badge" aria-hidden="true"></i>NIP {{ $personNip }}</span>
            @else
                <span><i class="fa fa-university" aria-hidden="true"></i>{{ __('SMA Negeri 11 Pulau Taliabu') }}</span>
            @endif
        </span>

        <h3 class="km-person-card__name">{{ $pengajars->name }}</h3>
    </div>
</article>
