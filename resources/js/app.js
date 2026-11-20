const registrationForm = document.querySelector('[data-registration-form]');

if (registrationForm) {
    const password = registrationForm.querySelector('#password');
    const confirmation = registrationForm.querySelector('#password_confirmation');
    const matchMessage = registrationForm.querySelector('[data-password-match]');
    const submitButton = registrationForm.querySelector('[data-submit-button]');
    const submitLabel = registrationForm.querySelector('[data-submit-label]');
    const loadingIndicator = registrationForm.querySelector('[data-loading-indicator]');

    registrationForm.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const input = registrationForm.querySelector(`#${toggle.dataset.passwordToggle}`);
            const shouldShow = input.type === 'password';

            input.type = shouldShow ? 'text' : 'password';
            toggle.textContent = shouldShow ? 'Hide' : 'Show';
            toggle.setAttribute('aria-pressed', String(shouldShow));
            toggle.setAttribute('aria-label', `${shouldShow ? 'Hide' : 'Show'} password`);
        });
    });

    const updatePasswordMatch = () => {
        if (!confirmation.value) {
            matchMessage.hidden = true;
            confirmation.removeAttribute('aria-invalid');

            return;
        }

        const passwordsMatch = password.value === confirmation.value;
        matchMessage.hidden = false;
        matchMessage.textContent = passwordsMatch ? 'Passwords match.' : 'Passwords do not match.';
        matchMessage.classList.toggle('field-error', !passwordsMatch);
        matchMessage.classList.toggle('field-hint', passwordsMatch);
        confirmation.setAttribute('aria-invalid', String(!passwordsMatch));
    };

    password.addEventListener('input', updatePasswordMatch);
    confirmation.addEventListener('input', updatePasswordMatch);

    registrationForm.addEventListener('submit', (event) => {
        if (submitButton.disabled) {
            event.preventDefault();

            return;
        }

        submitButton.disabled = true;
        submitLabel.textContent = 'Submitting…';
        loadingIndicator.hidden = false;
    });
}
