/* ==========================================================================
   km-drawer.js — pelengkap drawer navigasi HP/tablet (≤1199px)
   Tidak mengubah alur menu template: hanya menyinkronkan atribut ARIA,
   mengunci scroll latar, dan menyediakan tutup lewat tombol Escape.
   ========================================================================== */
(function () {
    'use strict';

    function initDrawerA11y() {
        var body = document.body;
        var toggle = document.querySelector('.km-backend-menu-toggle');
        var drawer = document.getElementById('main-menu-sidebar');

        if (!toggle || !drawer) {
            return;
        }

        var closeControl = drawer.querySelector('.modern-nav-toggle');

        var isOverlayMenu = function () {
            return body.classList.contains('vertical-overlay-menu');
        };

        var isDrawerOpen = function () {
            return isOverlayMenu() && body.classList.contains('menu-open');
        };

        var syncState = function () {
            var overlay = isOverlayMenu();
            var open = overlay && body.classList.contains('menu-open');

            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.documentElement.classList.toggle('km-drawer-open', open);

            if (overlay) {
                drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
            } else {
                drawer.removeAttribute('aria-hidden');
            }

            // Jangan biarkan fokus tertinggal di dalam panel yang sudah tersembunyi
            if (!open && drawer.contains(document.activeElement) && typeof toggle.focus === 'function') {
                toggle.focus();
            }
        };

        var closeDrawer = function () {
            var overlay = document.querySelector('.sidenav-overlay');
            var $ = window.jQuery;

            if ($ && overlay) {
                $(overlay).trigger('click');
            } else if (overlay) {
                overlay.click();
            }
        };

        if (closeControl) {
            closeControl.setAttribute('role', 'button');
            closeControl.setAttribute('tabindex', '0');
            closeControl.setAttribute('aria-label', 'Tutup menu navigasi');

            closeControl.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ' || event.key === 'Spacebar') {
                    event.preventDefault();
                    closeControl.click();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape' && event.key !== 'Esc') {
                return;
            }

            if (!isDrawerOpen()) {
                return;
            }

            event.preventDefault();
            closeDrawer();
        });

        // Template menandai keadaan drawer lewat class pada <body>
        if (typeof MutationObserver !== 'undefined') {
            new MutationObserver(syncState).observe(body, {
                attributes: true,
                attributeFilter: ['class'],
            });
        }

        var resizeTimer = null;
        window.addEventListener('resize', function () {
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(syncState, 150);
        });

        syncState();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDrawerA11y);
    } else {
        initDrawerA11y();
    }
})();
