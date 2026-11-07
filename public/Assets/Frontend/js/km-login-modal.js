(function () {
    'use strict';

    var modal = document.querySelector('[data-login-modal]');

    if (!modal) {
        return;
    }

    var frame = modal.querySelector('.km-login-modal__frame');
    var closeButton = modal.querySelector('.km-login-modal__close');
    var loadingIndicator = modal.querySelector('[data-login-modal-loading]');
    var activeTrigger = null;
    var frameLoaded = false;
    var loginUrlObject = new URL(
        document.querySelector('[data-login-modal-open]')?.href || '/login',
        window.location.origin
    );
    loginUrlObject.searchParams.set('modal', '1');
    var loginUrl = loginUrlObject.href;
    var loginPath = loginUrlObject.pathname;

    function preloadLoginForm() {
        if (frameLoaded || frame.src === loginUrl) {
            return;
        }

        loadingIndicator.hidden = false;
        frame.src = loginUrl;
    }

    function showLoginSubmissionFeedback() {
        var loginForm = frame.contentDocument.querySelector('.auth-login-form');

        if (!loginForm) {
            return;
        }

        loginForm.addEventListener('submit', function () {
            loadingIndicator.textContent = loadingIndicator.dataset.submittingText;
            loadingIndicator.dataset.submitting = 'true';
            loadingIndicator.hidden = false;

            var submitButton = loginForm.querySelector('button[type="submit"], button:not([type]), input[type="submit"]');

            if (submitButton) {
                submitButton.disabled = true;
            }
        }, { once: true });
    }

    function closeModal() {
        if (modal.hidden) {
            return;
        }
        modal.hidden = true;
        document.body.classList.remove('km-login-modal-open');

        if (activeTrigger) {
            var focusTarget = activeTrigger.closest('details.mobile-navigation')
                ? activeTrigger.closest('details.mobile-navigation').querySelector('.mobile-nav-toggle')
                : activeTrigger;

            if (focusTarget) {
                focusTarget.focus();
            }

            activeTrigger = null;
        }
    }

    document.addEventListener('click', function (event) {
        if (!(event.target instanceof Element)) {
            return;
        }

        var trigger = event.target.closest('[data-login-modal-open]');

        if (trigger) {
            if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
                return;
            }

            event.preventDefault();
            activeTrigger = trigger;
            modal.hidden = false;
            document.body.classList.add('km-login-modal-open');
            preloadLoginForm();

            document.querySelectorAll('details.mobile-navigation[open]').forEach(function (details) {
                details.removeAttribute('open');
            });

            closeButton.focus();
            return;
        }

        if (event.target.closest('[data-login-modal-close]')) {
            closeModal();
        }
    });

    frame.addEventListener('load', function () {
        try {
            var frameLocation = new URL(frame.contentWindow.location.href);

            if (frameLocation.origin !== window.location.origin) {
                return;
            }

            if (frameLocation.pathname === loginPath) {
                if (!frameLocation.searchParams.has('modal')) {
                    frameLoaded = false;
                    preloadLoginForm();
                    return;
                }

                frameLoaded = true;
                delete loadingIndicator.dataset.submitting;
                loadingIndicator.hidden = true;
                showLoginSubmissionFeedback();
                return;
            }

            if (!modal.hidden) {
                window.location.assign(frameLocation.href);
            }
        } catch (error) {
            if (!(error instanceof DOMException) || error.name !== 'SecurityError') {
                throw error;
            }
        }
    });

    document.addEventListener('pointerover', function (event) {
        if (event.target instanceof Element && event.target.closest('[data-login-modal-open]')) {
            preloadLoginForm();
        }
    });

    document.addEventListener('focusin', function (event) {
        if (event.target instanceof Element && event.target.closest('[data-login-modal-open]')) {
            preloadLoginForm();
        }
    });

    document.querySelectorAll('details.mobile-navigation').forEach(function (navigation) {
        navigation.addEventListener('toggle', function () {
            if (navigation.open) {
                preloadLoginForm();
            }
        });
    });

    function preloadOnMobileWhenReady() {
        var connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;

        if (!window.matchMedia('(max-width: 767px)').matches || connection?.saveData) {
            return;
        }

        preloadLoginForm();
    }

    preloadOnMobileWhenReady();

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
})();
