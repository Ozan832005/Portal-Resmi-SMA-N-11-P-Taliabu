/* ==========================================================================
   km-mobile-nav.js — pengelola drawer navigasi publik (≤767px)
   Melengkapi <details> native: animasi tutup, kunci scroll latar, tombol
   tutup, tap scrim, dan Escape. Tidak menyentuh rute atau logika aplikasi.
   ========================================================================== */
(function () {
    'use strict';

    function initMobileNav() {
        var details = document.querySelector('details.mobile-navigation');

        if (!details) {
            return;
        }

        var summary = details.querySelector('summary.mobile-nav-toggle');
        var closeButton = details.querySelector('.mobile-nav-close');
        var root = document.documentElement;
        var closeTimer = null;
        var prefersReducedMotion = window.matchMedia
            ? window.matchMedia('(prefers-reduced-motion: reduce)').matches
            : false;
        var closeDelay = prefersReducedMotion ? 0 : 210;

        var syncState = function () {
            var isOpen = details.open;

            root.classList.toggle('km-mobile-nav-open', isOpen);

            if (isOpen && closeButton) {
                window.setTimeout(function () {
                    if (details.open) {
                        closeButton.focus({ preventScroll: true });
                    }
                }, prefersReducedMotion ? 0 : 80);
            }
        };

        var closeDrawer = function () {
            if (!details.open || details.classList.contains('is-closing')) {
                return;
            }

            if (closeTimer) {
                window.clearTimeout(closeTimer);
            }

            details.classList.add('is-closing');

            closeTimer = window.setTimeout(function () {
                details.open = false;
                details.classList.remove('is-closing');
                closeTimer = null;

                if (summary) {
                    summary.focus({ preventScroll: true });
                }
            }, closeDelay);
        };

        details.addEventListener('toggle', function () {
            if (details.open) {
                if (closeTimer) {
                    window.clearTimeout(closeTimer);
                    closeTimer = null;
                }

                details.classList.remove('is-closing');
            }

            syncState();
        });

        if (summary) {
            summary.addEventListener('click', function (event) {
                // Saat sudah terbuka, tutup lewat animasi alih-alih snap native.
                if (details.open) {
                    event.preventDefault();
                    closeDrawer();
                }
            });
        }

        if (closeButton) {
            closeButton.addEventListener('click', function (event) {
                event.preventDefault();
                closeDrawer();
            });
        }

        // Scrim adalah pseudo-element <details>, jadi kliknya bertarget details.
        details.addEventListener('click', function (event) {
            if (event.target === details) {
                closeDrawer();
            }
        });

        document.addEventListener('keydown', function (event) {
            if ((event.key === 'Escape' || event.key === 'Esc') && details.open) {
                event.preventDefault();
                closeDrawer();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768 && details.open) {
                details.open = false;
            }
        });

        syncState();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileNav);
    } else {
        initMobileNav();
    }
})();
