/**
 * Kartu publikasi: tombol "Putar video" di bagian bawah kartu memicu tombol
 * putar yang menimpa thumbnail (pemutar inline), tanpa mengubah skrip pemutar
 * yang sudah ada di layouts/Frontend/scripts.blade.php.
 */
(function () {
    'use strict';

    document.addEventListener('click', function (event) {
        if (!(event.target instanceof Element)) {
            return;
        }

        var trigger = event.target.closest('[data-video-trigger]');

        if (!trigger) {
            return;
        }

        var card = trigger.closest('.km-video-card');

        if (!card) {
            return;
        }

        var playButton = card.querySelector('.km-publication-play-button');

        if (playButton) {
            playButton.click();
            card.scrollIntoView({ block: 'center', behavior: 'smooth' });

            return;
        }

        var fallback = card.querySelector('.km-publication-fallback-link, .km-publication-link');

        if (fallback) {
            fallback.click();
        }
    });
})();
