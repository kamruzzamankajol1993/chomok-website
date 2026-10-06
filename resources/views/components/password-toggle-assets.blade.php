<style>
.password-input-wrap {
    position: relative;
    display: block;
    width: 100%;
}

.password-input-wrap > input[data-password-managed="1"] {
    width: 100%;
    padding-right: 4.75rem !important;
}

.password-toggle,
.password-visibility-toggle {
    position: absolute;
    top: 50%;
    right: .65rem;
    transform: translateY(-50%);
    z-index: 10;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    min-height: 2rem;
    border: 0;
    background: transparent;
    color: inherit;
    opacity: .78;
    cursor: pointer;
    padding: .25rem .35rem;
    line-height: 1;
    font-size: .82rem;
    font-weight: 700;
}

.password-toggle:hover,
.password-visibility-toggle:hover,
.password-toggle:focus-visible,
.password-visibility-toggle:focus-visible {
    opacity: 1;
    outline: none;
}

.password-toggle-text {
    font-size: .72rem;
    line-height: 1;
}
</style>

<script>
(function () {
    'use strict';

    function getInputForButton(button) {
        if (!button) return null;

        const selector = button.getAttribute('data-password-toggle');
        if (selector) {
            try {
                const selected = document.querySelector(selector);
                if (selected) return selected;
            } catch (error) {
                // Fall back to the nearest password wrapper if the selector is invalid.
            }
        }

        const wrapper = button.closest('.password-input-wrap');
        return wrapper ? wrapper.querySelector('input[data-password-managed="1"], input[type="password"], input[type="text"]') : null;
    }

    function updateToggle(button, input) {
        if (!button || !input) return;

        const visible = input.type === 'text';
        button.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
        button.setAttribute('title', visible ? 'Hide password' : 'Show password');
        button.setAttribute('aria-pressed', visible ? 'true' : 'false');

        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('bi-eye', !visible);
            icon.classList.toggle('bi-eye-slash', visible);
        }

        const text = button.querySelector('.password-toggle-text');
        if (text) {
            text.textContent = visible ? 'Hide' : 'Show';
        }
    }

    function preparePasswordInput(input) {
        if (!input || input.dataset.passwordManaged === '1') {
            return;
        }

        let wrapper = input.parentElement;
        if (!wrapper || !wrapper.classList.contains('password-input-wrap')) {
            const newWrapper = document.createElement('div');
            newWrapper.className = 'password-input-wrap';
            input.parentNode.insertBefore(newWrapper, input);
            newWrapper.appendChild(input);
            wrapper = newWrapper;
        }

        input.dataset.passwordManaged = '1';

        let button = wrapper.querySelector('[data-password-toggle]');
        if (!button) {
            button = document.createElement('button');
            button.type = 'button';
            button.className = 'password-visibility-toggle';
            button.setAttribute('data-password-toggle', '');
            button.innerHTML = '<span class="password-toggle-text">Show</span>';
            wrapper.appendChild(button);
        }

        updateToggle(button, input);
    }

    function initializePasswordFields(root) {
        const scope = root && root.querySelectorAll ? root : document;

        if (scope.matches && scope.matches('input[type="password"]')) {
            preparePasswordInput(scope);
        }

        scope.querySelectorAll('input[type="password"]').forEach(preparePasswordInput);

        scope.querySelectorAll('[data-password-toggle]').forEach(function (button) {
            const input = getInputForButton(button);
            if (input) {
                input.dataset.passwordManaged = '1';
                updateToggle(button, input);
            }
        });
    }

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-password-toggle]');
        if (!button) return;

        const input = getInputForButton(button);
        if (!input) return;

        event.preventDefault();
        event.stopPropagation();

        input.type = input.type === 'password' ? 'text' : 'password';
        input.dataset.passwordManaged = '1';
        updateToggle(button, input);

        try {
            input.focus({ preventScroll: true });
        } catch (error) {
            input.focus();
        }
    }, false);

    function start() {
        initializePasswordFields(document);

        if (!document.documentElement || typeof MutationObserver === 'undefined') {
            return;
        }

        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.nodeType === 1) {
                        initializePasswordFields(node);
                    }
                });
            });
        });

        observer.observe(document.documentElement, {
            childList: true,
            subtree: true,
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start, { once: true });
    } else {
        start();
    }
})();
</script>
