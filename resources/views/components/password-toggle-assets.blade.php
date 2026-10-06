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
    z-index: 5;
    border: 0;
    background: transparent;
    color: inherit;
    opacity: .72;
    cursor: pointer;
    padding: .25rem .35rem;
    line-height: 1;
    font-size: .78rem;
    font-weight: 700;
}
.password-toggle:hover,
.password-visibility-toggle:hover,
.password-toggle:focus-visible,
.password-visibility-toggle:focus-visible {
    opacity: 1;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateToggle(button, input) {
        const visible = input.type === 'text';
        button.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
        button.setAttribute('aria-pressed', visible ? 'true' : 'false');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('bi-eye', !visible);
            icon.classList.toggle('bi-eye-slash', visible);
        } else {
            button.textContent = visible ? 'Hide' : 'Show';
        }
    }

    function bindToggle(button, input) {
        if (!button || !input || button.dataset.passwordBound === '1') return;
        button.dataset.passwordBound = '1';
        input.dataset.passwordManaged = '1';
        updateToggle(button, input);
        button.addEventListener('click', function () {
            input.type = input.type === 'password' ? 'text' : 'password';
            updateToggle(button, input);
            input.focus({ preventScroll: true });
        });
    }

    document.querySelectorAll('input[type="password"]').forEach(function (input, index) {
        let wrapper = input.parentElement;
        let button = wrapper ? wrapper.querySelector('[data-password-toggle]') : null;

        if (!wrapper || !wrapper.classList.contains('password-input-wrap')) {
            const newWrapper = document.createElement('div');
            newWrapper.className = 'password-input-wrap';
            input.parentNode.insertBefore(newWrapper, input);
            newWrapper.appendChild(input);
            wrapper = newWrapper;
        }

        if (!button) {
            button = document.createElement('button');
            button.type = 'button';
            button.className = 'password-visibility-toggle';
            button.setAttribute('data-password-toggle', '');
            wrapper.appendChild(button);
        }

        bindToggle(button, input);
    });

    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        if (button.dataset.passwordBound === '1') return;
        const selector = button.getAttribute('data-password-toggle');
        const input = selector ? document.querySelector(selector) : button.parentElement?.querySelector('input');
        bindToggle(button, input);
    });
});
</script>
