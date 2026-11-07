/**
 * Komponen berita publik:
 *  1. Modal "Baca Berita" (dibuka dari kartu berita beranda & halaman Berita).
 *  2. Slider hero berita di halaman Berita.
 * Keduanya hanya aktif jika elemennya ada di halaman.
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------
       Modal baca berita
       ------------------------------------------------------------------ */
    var modal = document.querySelector('[data-news-modal]');

    if (modal) {
        var scroller = modal.querySelector('[data-news-modal-scroll]');
        var dialog = modal.querySelector('.km-news-modal__dialog');
        var slots = {
            cat: modal.querySelector('[data-news-slot="cat"]'),
            title: modal.querySelector('[data-news-slot="title"]'),
            body: modal.querySelector('[data-news-slot="body"]'),
            media: modal.querySelector('[data-news-slot="media"]'),
            image: modal.querySelector('[data-news-slot="image"]'),
            detail: modal.querySelector('[data-news-slot="detail"]'),
            author: modal.querySelector('[data-news-slot="author"]'),
            date: modal.querySelector('[data-news-slot="date"]'),
            views: modal.querySelector('[data-news-slot="views"]'),
            read: modal.querySelector('[data-news-slot="read"]')
        };
        var shareLinks = Array.prototype.slice.call(modal.querySelectorAll('[data-news-share]'));
        var copyButton = modal.querySelector('[data-news-copy]');
        var closeButton = modal.querySelector('.km-news-modal__close');
        var lastTrigger = null;

        var setMeta = function (item, value) {
            if (!item) {
                return;
            }

            var label = item.querySelector('span');
            var text = (value || '').trim();

            item.hidden = text === '';

            if (label) {
                label.textContent = text;
            }
        };

        var setShareTargets = function (title, url) {
            var encodedTitle = encodeURIComponent(title);
            var encodedUrl = encodeURIComponent(url);

            var targets = {
                whatsapp: 'https://wa.me/?text=' + encodeURIComponent(title + ' ' + url),
                x: 'https://twitter.com/intent/tweet?text=' + encodedTitle + '&url=' + encodedUrl,
                facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + encodedUrl,
                telegram: 'https://t.me/share/url?url=' + encodedUrl + '&text=' + encodedTitle
            };

            shareLinks.forEach(function (link) {
                var network = link.getAttribute('data-news-share');
                link.href = targets[network] || url;
            });

            if (copyButton) {
                copyButton.setAttribute('data-news-url', url);
            }
        };

        var openModal = function (trigger) {
            var card = trigger.closest('.km-news-card');
            var template = card ? card.querySelector('template[data-news-content]') : null;
            var title = trigger.getAttribute('data-news-title') || '';
            var url = trigger.getAttribute('data-news-url') || window.location.href;
            var image = trigger.getAttribute('data-news-image') || '';

            lastTrigger = trigger;

            if (slots.cat) {
                slots.cat.textContent = trigger.getAttribute('data-news-cat') || '';
            }

            if (slots.title) {
                slots.title.textContent = title;
            }

            setMeta(slots.author, trigger.getAttribute('data-news-author'));
            setMeta(slots.date, trigger.getAttribute('data-news-date'));
            setMeta(slots.views, trigger.getAttribute('data-news-views'));
            setMeta(slots.read, trigger.getAttribute('data-news-read'));

            if (slots.image) {
                slots.image.src = image;
                slots.image.alt = title;
            }

            if (slots.media) {
                slots.media.hidden = image === '';
            }

            if (slots.body) {
                slots.body.innerHTML = '';

                if (template && template.content) {
                    slots.body.appendChild(template.content.cloneNode(true));
                }
            }

            if (slots.detail) {
                slots.detail.href = url;
            }

            setShareTargets(title, url);

            if (copyButton) {
                copyButton.classList.remove('is-copied');
                copyButton.setAttribute('aria-label', copyButton.getAttribute('data-copy-label') || 'Salin tautan');
            }

            modal.hidden = false;
            document.documentElement.classList.add('km-news-lock');

            if (scroller) {
                scroller.scrollTop = 0;
            }

            if (closeButton) {
                closeButton.focus();
            }
        };

        var closeModal = function () {
            if (modal.hidden) {
                return;
            }

            modal.hidden = true;
            document.documentElement.classList.remove('km-news-lock');

            if (lastTrigger) {
                lastTrigger.focus({ preventScroll: true });
                lastTrigger = null;
            }
        };

        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-news-open]');

            if (trigger) {
                event.preventDefault();
                openModal(trigger);

                return;
            }

            if (event.target.closest('[data-news-close]')) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (modal.hidden) {
                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                closeModal();

                return;
            }

            if (event.key !== 'Tab' || !dialog) {
                return;
            }

            var focusable = Array.prototype.slice
                .call(dialog.querySelectorAll('a[href], button:not([disabled]), input, [tabindex]:not([tabindex="-1"])'))
                .filter(function (element) {
                    return element.offsetParent !== null;
                });

            if (!focusable.length) {
                return;
            }

            var first = focusable[0];
            var last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });

        if (copyButton) {
            copyButton.addEventListener('click', function () {
                var url = copyButton.getAttribute('data-news-url') || window.location.href;
                var feedbackLabel = copyButton.getAttribute('data-copied-label') || 'Tautan disalin';

                var showFeedback = function () {
                    copyButton.classList.add('is-copied');
                    copyButton.setAttribute('aria-label', feedbackLabel);

                    window.setTimeout(function () {
                        copyButton.classList.remove('is-copied');
                        copyButton.setAttribute('aria-label', copyButton.getAttribute('data-copy-label') || 'Salin tautan');
                    }, 1900);
                };

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(showFeedback, showFeedback);

                    return;
                }

                var helper = document.createElement('textarea');
                helper.value = url;
                helper.setAttribute('readonly', 'readonly');
                helper.style.position = 'fixed';
                helper.style.opacity = '0';
                document.body.appendChild(helper);
                helper.select();

                try {
                    document.execCommand('copy');
                } catch (error) {
                    /* diabaikan: browser menolak akses clipboard */
                }

                document.body.removeChild(helper);
                showFeedback();
            });
        }
    }

    /* ------------------------------------------------------------------
       Tombol "salin tautan" di halaman detail berita
       ------------------------------------------------------------------ */
    var copyButtons = Array.prototype.slice.call(document.querySelectorAll('[data-copy-link]'));

    var copyToClipboard = function (button) {
        var url = button.getAttribute('data-copy-link') || window.location.href;
        var defaultLabel = button.getAttribute('data-copy-label') || 'Salin tautan berita';
        var copiedLabel = button.getAttribute('data-copied-label') || 'Tautan disalin';

        var showFeedback = function () {
            button.classList.add('is-copied');
            button.setAttribute('aria-label', copiedLabel);

            window.setTimeout(function () {
                button.classList.remove('is-copied');
                button.setAttribute('aria-label', defaultLabel);
            }, 1900);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(showFeedback, showFeedback);

            return;
        }

        var helper = document.createElement('textarea');
        helper.value = url;
        helper.setAttribute('readonly', 'readonly');
        helper.style.position = 'fixed';
        helper.style.opacity = '0';
        document.body.appendChild(helper);
        helper.select();

        try {
            document.execCommand('copy');
        } catch (error) {
            /* diabaikan: browser menolak akses clipboard */
        }

        document.body.removeChild(helper);
        showFeedback();
    };

    copyButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            copyToClipboard(button);
        });
    });

    /* ------------------------------------------------------------------
       Slider hero berita (halaman Berita)
       ------------------------------------------------------------------ */
    var feature = document.querySelector('[data-news-feature]');

    if (feature) {
        var slides = Array.prototype.slice.call(feature.querySelectorAll('[data-news-feature-slide]'));
        var dots = Array.prototype.slice.call(feature.querySelectorAll('[data-news-feature-dot]'));
        var prev = feature.querySelector('[data-news-feature-prev]');
        var next = feature.querySelector('[data-news-feature-next]');
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var current = 0;
        var timer = null;
        var AUTOPLAY_TIME = 7000;

        var showSlide = function (index) {
            if (!slides.length) {
                return;
            }

            current = (index + slides.length) % slides.length;

            slides.forEach(function (slide, position) {
                slide.classList.toggle('is-active', position === current);
                slide.setAttribute('aria-hidden', position === current ? 'false' : 'true');
            });

            dots.forEach(function (dot, position) {
                var isActive = position === current;
                dot.classList.toggle('is-active', isActive);
                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
        };

        var stopAutoplay = function () {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        };

        var startAutoplay = function () {
            if (reduceMotion || timer || slides.length < 2) {
                return;
            }

            timer = window.setInterval(function () {
                if (!document.hidden) {
                    showSlide(current + 1);
                }
            }, AUTOPLAY_TIME);
        };

        if (prev) {
            prev.addEventListener('click', function () {
                showSlide(current - 1);
                stopAutoplay();
                startAutoplay();
            });
        }

        if (next) {
            next.addEventListener('click', function () {
                showSlide(current + 1);
                stopAutoplay();
                startAutoplay();
            });
        }

        dots.forEach(function (dot, position) {
            dot.addEventListener('click', function () {
                showSlide(position);
                stopAutoplay();
                startAutoplay();
            });
        });

        feature.addEventListener('mouseenter', stopAutoplay);
        feature.addEventListener('mouseleave', startAutoplay);
        feature.addEventListener('focusin', stopAutoplay);
        feature.addEventListener('focusout', startAutoplay);

        showSlide(0);
        startAutoplay();
    }
})();
