(function () {
    'use strict';

    var dialog = document.getElementById('km-delete-dialog');

    if (!dialog) {
        return;
    }

    var message = dialog.querySelector('#km-delete-dialog-message');
    var cancelButtons = dialog.querySelectorAll('[data-delete-cancel]');
    var confirmButton = dialog.querySelector('[data-delete-confirm]');
    var pendingForm = null;
    var pendingSubmitter = null;
    var previousFocus = null;

    function closeDialog() {
        dialog.hidden = true;
        dialog.setAttribute('aria-hidden', 'true');
        pendingForm = null;
        pendingSubmitter = null;

        if (previousFocus && typeof previousFocus.focus === 'function') {
            previousFocus.focus();
        }
    }

    function openDialog(form, submitter) {
        pendingForm = form;
        pendingSubmitter = submitter;
        previousFocus = document.activeElement;
        message.textContent = form.getAttribute('data-delete-message') ||
            'Data yang dihapus tidak dapat dikembalikan.';
        dialog.hidden = false;
        dialog.setAttribute('aria-hidden', 'false');
        dialog.querySelector('.km-delete-dialog__cancel').focus();
    }

    document.addEventListener('submit', function (event) {
        var form = event.target;
        var method = form.querySelector('input[name="_method"]');

        if (!method || method.value.toUpperCase() !== 'DELETE') {
            return;
        }

        if (form.dataset.deleteConfirmed === 'true') {
            delete form.dataset.deleteConfirmed;
            return;
        }

        event.preventDefault();
        openDialog(form, event.submitter || null);
    });

    Array.prototype.forEach.call(cancelButtons, function (button) {
        button.addEventListener('click', closeDialog);
    });

    confirmButton.addEventListener('click', function () {
        if (!pendingForm) {
            return;
        }

        var form = pendingForm;
        var submitter = pendingSubmitter;
        form.dataset.deleteConfirmed = 'true';
        closeDialog();

        if (typeof form.requestSubmit === 'function') {
            if (submitter && submitter.form === form) {
                form.requestSubmit(submitter);
            } else {
                form.requestSubmit();
            }
        } else {
            HTMLFormElement.prototype.submit.call(form);
        }
    });

    dialog.addEventListener('click', function (event) {
        if (event.target.classList.contains('km-delete-dialog__backdrop')) {
            closeDialog();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (dialog.hidden) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            closeDialog();
            return;
        }

        if (event.key === 'Tab') {
            var focusable = [dialog.querySelector('.km-delete-dialog__cancel'), confirmButton];
            var currentIndex = focusable.indexOf(document.activeElement);

            if (event.shiftKey && currentIndex <= 0) {
                event.preventDefault();
                confirmButton.focus();
            } else if (!event.shiftKey && currentIndex === focusable.length - 1) {
                event.preventDefault();
                focusable[0].focus();
            }
        }
    });
})();
