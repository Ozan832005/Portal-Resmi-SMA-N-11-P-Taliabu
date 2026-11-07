(function () {
    'use strict';

    var root = document.documentElement;
    var themeButtons = document.querySelectorAll('[data-theme-toggle]');

    function syncThemeButtons(theme) {
        themeButtons.forEach(function (button) {
            var isDark = theme === 'dark';

            button.dataset.theme = theme;
            button.setAttribute('aria-pressed', String(isDark));
            button.setAttribute(
                'aria-label',
                isDark
                    ? button.dataset.labelLight || 'Aktifkan mode terang'
                    : button.dataset.labelDark || 'Aktifkan mode gelap'
            );
        });
    }

    function applyTheme(theme, shouldPersist) {
        root.dataset.theme = theme;
        syncThemeButtons(theme);

        if (!shouldPersist) {
            return;
        }

        try {
            localStorage.setItem('km-theme', theme);
        } catch (error) {
            console.warn('Preferensi tema tidak dapat disimpan di browser.', error);
        }
    }

    applyTheme(root.dataset.theme === 'dark' ? 'dark' : 'light', false);

    themeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            applyTheme(root.dataset.theme === 'dark' ? 'light' : 'dark', true);
        });
    });

    window.addEventListener('storage', function (event) {
        if (event.key === 'km-theme' && (event.newValue === 'dark' || event.newValue === 'light')) {
            applyTheme(event.newValue, false);
        }
    });
})();
