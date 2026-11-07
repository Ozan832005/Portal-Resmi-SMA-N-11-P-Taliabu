/**
 * Hero halaman depan: animasi slogan (ketik - hapus - ulang) dan pergantian
 * gambar slider di dalam bingkai. Berjalan hanya jika elemen hero ada.
 */
(function () {
    'use strict';

    var hero = document.querySelector('[data-hero-root]') || document.querySelector('.km-hero[data-km-hero]');

    if (!hero) {
        return;
    }

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------------------------------------------------------------
       Slogan: ketik, tahan, hapus, ulang
       --------------------------------------------------------------- */
    var typed = hero.querySelector('[data-hero-typing]');
    var slogan = (hero.getAttribute('data-slogan') || '').trim();
    var phrases = slogan.split('|').map(function (part) {
        return part.trim();
    }).filter(Boolean);

    if (typed && phrases.length) {
        if (reduceMotion) {
            typed.textContent = phrases[0];
        } else {
            var phraseIndex = 0;
            var charIndex = 0;
            var deleting = false;

            var TYPE_SPEED = 85;
            var DELETE_SPEED = 42;
            var HOLD_TIME = 1900;
            var GAP_TIME = 420;

            var step = function () {
                var current = phrases[phraseIndex];

                if (!deleting) {
                    charIndex += 1;
                    typed.textContent = current.slice(0, charIndex);

                    if (charIndex >= current.length) {
                        deleting = true;
                        window.setTimeout(tick, HOLD_TIME);

                        return;
                    }

                    window.setTimeout(tick, TYPE_SPEED);

                    return;
                }

                charIndex -= 1;
                typed.textContent = current.slice(0, Math.max(charIndex, 0));

                if (charIndex <= 0) {
                    deleting = false;
                    phraseIndex = (phraseIndex + 1) % phrases.length;
                    window.setTimeout(tick, GAP_TIME);

                    return;
                }

                window.setTimeout(tick, DELETE_SPEED);
            };

            var tick = function () {
                if (document.hidden) {
                    window.setTimeout(tick, 600);

                    return;
                }

                step();
            };

            window.setTimeout(tick, 400);
        }
    }

    /* ---------------------------------------------------------------
       Cahaya biru yang mengikuti kursor di latar hero
       --------------------------------------------------------------- */
    var area = hero.closest('.slider1-area') || hero.parentNode;
    var fx = area ? area.querySelector('[data-hero-fx]') : null;
    var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    if (fx && area && !reduceMotion && finePointer) {
        var rect = null;
        var targetX = 0;
        var targetY = 0;
        var currentX = null;
        var currentY = null;
        var frame = null;

        var clampValue = function (value, min, max) {
            return Math.min(Math.max(value, min), max);
        };

        var paintLight = function () {
            frame = null;

            if (currentX === null) {
                currentX = targetX;
                currentY = targetY;
            }

            currentX += (targetX - currentX) * 0.18;
            currentY += (targetY - currentY) * 0.18;

            fx.style.setProperty('--km-px', Math.round(currentX) + 'px');
            fx.style.setProperty('--km-py', Math.round(currentY) + 'px');

            if (Math.abs(targetX - currentX) > 0.5 || Math.abs(targetY - currentY) > 0.5) {
                frame = window.requestAnimationFrame(paintLight);
            }
        };

        var moveLight = function (event) {
            if (!rect) {
                rect = area.getBoundingClientRect();
            }

            targetX = clampValue(event.clientX - rect.left, 0, rect.width);
            targetY = clampValue(event.clientY - rect.top, 0, rect.height);
            fx.classList.add('is-live');

            if (!frame) {
                frame = window.requestAnimationFrame(paintLight);
            }
        };

        area.addEventListener('pointermove', moveLight, { passive: true });
        area.addEventListener('pointerenter', moveLight, { passive: true });
        area.addEventListener('pointerdown', moveLight, { passive: true });

        area.addEventListener('pointerleave', function () {
            fx.classList.remove('is-live');
        });

        window.addEventListener('scroll', function () {
            rect = null;
        }, { passive: true });

        window.addEventListener('resize', function () {
            rect = null;
        });
    }

    /* ---------------------------------------------------------------
       Gambar slider + titik navigasi
       --------------------------------------------------------------- */
    var slides = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-slide]'));
    var copies = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-copy]'));
    var dots = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-dot]'));

    if (slides.length < 2) {
        return;
    }

    var activeIndex = 0;
    var autoplay = null;
    var AUTOPLAY_TIME = 6500;

    var show = function (next) {
        activeIndex = (next + slides.length) % slides.length;

        slides.forEach(function (slide, index) {
            slide.classList.toggle('is-active', index === activeIndex);
        });

        copies.forEach(function (block, index) {
            block.classList.toggle('is-active', index === activeIndex);
        });

        dots.forEach(function (dot, index) {
            var isActive = index === activeIndex;
            dot.classList.toggle('is-active', isActive);
            dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
    };

    var start = function () {
        if (reduceMotion || autoplay) {
            return;
        }

        autoplay = window.setInterval(function () {
            if (!document.hidden) {
                show(activeIndex + 1);
            }
        }, AUTOPLAY_TIME);
    };

    var stop = function () {
        if (autoplay) {
            window.clearInterval(autoplay);
            autoplay = null;
        }
    };

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            stop();
            show(index);
            start();
        });
    });

    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    hero.addEventListener('focusin', stop);
    hero.addEventListener('focusout', start);

    show(0);
    start();
})();
