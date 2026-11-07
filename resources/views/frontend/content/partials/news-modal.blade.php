{{-- Modal baca berita. Isinya diisi oleh Assets/Frontend/js/km-news.js --}}
<div class="km-news-modal" data-news-modal hidden>
    <div class="km-news-modal__overlay" data-news-close></div>

    <div class="km-news-modal__dialog"
         role="dialog"
         aria-modal="true"
         aria-labelledby="km-news-modal-title"
         aria-describedby="km-news-modal-meta">
        <button type="button" class="km-news-modal__close" data-news-close aria-label="{{ __('Tutup berita') }}">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>

        <div class="km-news-modal__scroll" data-news-modal-scroll>
            <span class="km-news-modal__cat" data-news-slot="cat"></span>

            <h2 class="km-news-modal__title" id="km-news-modal-title" data-news-slot="title"></h2>

            <ul class="km-news-modal__meta" id="km-news-modal-meta">
                <li data-news-slot="author" hidden><i class="fa fa-user-o" aria-hidden="true"></i><span></span></li>
                <li data-news-slot="date" hidden><i class="fa fa-calendar-o" aria-hidden="true"></i><span></span></li>
                <li data-news-slot="views" hidden><i class="fa fa-eye" aria-hidden="true"></i><span></span></li>
                <li data-news-slot="read" hidden><i class="fa fa-clock-o" aria-hidden="true"></i><span></span></li>
            </ul>

            <div class="km-news-share">
                <span class="km-news-share__label">{{ __('Bagikan') }}</span>
                <a class="km-news-share-btn km-news-share-btn--wa" data-news-share="whatsapp" href="#" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke WhatsApp') }}"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                <a class="km-news-share-btn km-news-share-btn--x" data-news-share="x" href="#" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke X') }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a class="km-news-share-btn km-news-share-btn--fb" data-news-share="facebook" href="#" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke Facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a class="km-news-share-btn km-news-share-btn--tg" data-news-share="telegram" href="#" target="_blank" rel="noopener" aria-label="{{ __('Bagikan ke Telegram') }}"><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
                <button type="button"
                        class="km-news-share-btn km-news-share-btn--copy"
                        data-news-copy
                        data-copy-label="{{ __('Salin tautan berita') }}"
                        data-copied-label="{{ __('Tautan disalin') }}"
                        aria-label="{{ __('Salin tautan berita') }}">
                    <i class="fa fa-link" aria-hidden="true"></i>
                </button>
            </div>

            <figure class="km-news-modal__media" data-news-slot="media" hidden>
                <img src="" alt="" data-news-slot="image">
            </figure>

            <div class="km-news-prose" data-news-slot="body"></div>

            <a class="km-news-modal__cta" data-news-slot="detail" href="#">
                {{ __('Baca di halaman berita') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>
