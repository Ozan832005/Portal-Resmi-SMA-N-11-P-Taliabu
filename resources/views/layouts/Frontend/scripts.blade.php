<!-- jquery-->
<script src="{{asset('Assets/Frontend/js/jquery-2.2.4.min.js')}}" type="text/javascript"></script>
<!-- Plugins js -->
<script src="{{asset('Assets/Frontend/js/plugins.js')}}" type="text/javascript"></script>
<!-- Bootstrap js -->
<script src="{{asset('Assets/Frontend/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- WOW JS -->
<script src="{{asset('Assets/Frontend/js/wow.min.js')}}"></script>
<!-- Nivo slider js -->
<script src="{{asset('Assets/Frontend/vendor/slider/js/jquery.nivo.slider.js')}}" type="text/javascript"></script>
<script src="{{asset('Assets/Frontend/vendor/slider/home.js')}}" type="text/javascript"></script>
<!-- Owl Cauosel JS -->
<script src="{{asset('Assets/Frontend/vendor/OwlCarousel/owl.carousel.min.js')}}" type="text/javascript"></script>
<!-- Srollup js -->
<script src="{{asset('Assets/Frontend/js/jquery.scrollUp.min.js')}}" type="text/javascript"></script>
<!-- jquery.counterup js -->
<script src="{{asset('Assets/Frontend/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('Assets/Frontend/js/waypoints.min.js')}}"></script>
<!-- Select2 Js -->
<script src="{{asset('Assets/Frontend/js/select2.min.js')}}" type="text/javascript"></script>
<!-- Countdown js -->
<script src="{{asset('Assets/Frontend/js/jquery.countdown.min.js')}}" type="text/javascript"></script>
<!-- Isotope js -->
<script src="{{asset('Assets/Frontend/js/isotope.pkgd.min.js')}}" type="text/javascript"></script>
<!-- Magic Popup js -->
<script src="{{asset('Assets/Frontend/js/jquery.magnific-popup.min.js')}}" type="text/javascript"></script>
<!-- Gridrotator js -->
<script src="{{asset('Assets/Frontend/js/jquery.gridrotator.js')}}" type="text/javascript"></script>
<!-- Custom Js -->
<script src="{{asset('Assets/Frontend/js/main.js')}}" type="text/javascript"></script>
<!-- Drawer navigasi publik (mobile) -->
<script src="{{ asset('Assets/Frontend/js/km-mobile-nav.js') }}?v={{ filemtime(public_path('Assets/Frontend/js/km-mobile-nav.js')) }}"></script>
<!-- Hero halaman depan: slogan animasi + pergantian gambar -->
<script src="{{ asset('Assets/Frontend/js/km-hero.js') }}?v={{ filemtime(public_path('Assets/Frontend/js/km-hero.js')) }}"></script>
<!-- Berita: modal baca berita + slider hero berita -->
<script src="{{ asset('Assets/Frontend/js/km-news.js') }}?v={{ filemtime(public_path('Assets/Frontend/js/km-news.js')) }}"></script>
<!-- Kartu publikasi: tombol putar video -->
<script src="{{ asset('Assets/Frontend/js/km-cards.js') }}?v={{ filemtime(public_path('Assets/Frontend/js/km-cards.js')) }}"></script>
<!-- Pengaturan tema terang/gelap -->
<script src="{{ asset('Assets/Frontend/js/km-theme.js') }}?v={{ filemtime(public_path('Assets/Frontend/js/km-theme.js')) }}"></script>
<script>
    document.addEventListener('click', function (event) {
        if (!(event.target instanceof Element)) {
            return;
        }

        const alumniControl = event.target.closest('[data-alumni-scroll]');

        if (alumniControl) {
            const track = document.getElementById('alumni-track');
            const firstCard = track?.querySelector('.km-alumni-card');

            if (track && firstCard) {
                const direction = Number(alumniControl.dataset.alumniScroll);
                const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;

                track.scrollBy({
                    left: (firstCard.getBoundingClientRect().width + gap) * direction,
                    behavior: 'smooth',
                });
            }

            return;
        }

        const button = event.target.closest('.km-publication-play-button');

        if (!button) {
            return;
        }

        const media = button.closest('.km-publication-media');

        if (!media) {
            return;
        }

        const iframe = document.createElement('iframe');
        iframe.src = button.dataset.embedUrl + '&autoplay=1&playsinline=1';
        iframe.title = button.dataset.videoTitle;
        iframe.loading = 'eager';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        iframe.allowFullscreen = true;
        media.replaceChildren(iframe);
    });
</script>
<script>
    document.addEventListener('click', function (event) {
        if (!(event.target instanceof Element)) {
            return;
        }

        document.querySelectorAll('details.mobile-navigation[open]').forEach(function (details) {
            if (!details.contains(event.target)) {
                details.removeAttribute('open');
            }
        });
    });
</script>