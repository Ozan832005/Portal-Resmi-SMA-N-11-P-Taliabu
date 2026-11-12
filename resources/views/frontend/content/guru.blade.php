<div class="lecturers-area">
    <div class="container">
        <header class="km-news-head">
            <p class="km-news-head__eyebrow">{{ __('Tenaga pendidik') }}</p>
            <h2 class="km-news-head__title">{{ __('Pengajar') }}</h2>
            <span class="km-news-head__rule" aria-hidden="true"></span>
            <p class="km-news-head__sub">{{ __('Kenali para pendidik yang membimbing siswa') }}</p>
        </header>
    </div>

    @if ($pengajar->isEmpty())
        <div class="container">
            <div class="km-news-empty">
                <i class="fa fa-users" aria-hidden="true"></i>
                <strong>{{ __('Data pengajar belum tersedia') }}</strong>
                <span>{{ __('Tenaga pendidik yang terdaftar akan tampil di sini.') }}</span>
            </div>
        </div>
    @else
        <div class="container">
            <div class="rc-carousel km-teacher-carousel" role="region" aria-label="{{ __('Geser untuk melihat profil pengajar') }}" data-loop="true" data-items="4" data-margin="22" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="700" data-dots="true" data-nav="false" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="false" data-r-x-small-dots="true" data-r-x-medium="1" data-r-x-medium-nav="false" data-r-x-medium-dots="true" data-r-small="2" data-r-small-nav="false" data-r-small-dots="true" data-r-medium="4" data-r-medium-nav="false" data-r-medium-dots="true" data-r-large="4" data-r-large-nav="false" data-r-large-dots="true">
                @foreach ($pengajar as $pengajars)
                    <div class="single-item km-person-slide">
                        @include('frontend.content.partials.person-card', ['pengajars' => $pengajars])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
