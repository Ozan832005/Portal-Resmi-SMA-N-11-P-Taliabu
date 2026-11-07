(function () {
    'use strict';

    var aboutArea = document.querySelector('.about1-area');
    var background = aboutArea ? aboutArea.querySelector('.km-about-fx') : null;

    if (!aboutArea || !background) {
        return;
    }

    aboutArea.addEventListener('pointermove', function (event) {
        if (event.pointerType === 'touch') {
            return;
        }

        var bounds = aboutArea.getBoundingClientRect();
        var x = ((event.clientX - bounds.left) / bounds.width) * 100;
        var y = ((event.clientY - bounds.top) / bounds.height) * 100;

        background.style.setProperty('--km-about-pointer-x', x + '%');
        background.style.setProperty('--km-about-pointer-y', y + '%');
        background.style.setProperty('--km-about-pointer-opacity', '0.38');
    });

    aboutArea.addEventListener('pointerleave', function () {
        background.style.setProperty('--km-about-pointer-opacity', '0');
    });
})();
