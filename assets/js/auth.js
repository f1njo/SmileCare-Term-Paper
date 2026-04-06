document.addEventListener('DOMContentLoaded', () => {
    setupPasswordToggles();
    setupAuthForms();
});

function setupPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = button.parentElement?.querySelector('input');
            if (!input) {
                return;
            }

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.textContent = isPassword ? 'Скрыть' : 'Показать';
        });
    });
}

function setupAuthForms() {
    document.querySelectorAll('[data-auth-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearFormErrors(form);

            const formData = new FormData(form);
            const errors = validateAuthForm(form, formData);
            if (Object.keys(errors).length > 0) {
                applyErrors(form, errors);
                return;
            }

            const submitButton = form.querySelector('[type="submit"]');
            const message = form.querySelector('[data-form-message]');

            if (submitButton) {
                submitButton.disabled = true;
            }

            if (message) {
                message.textContent = 'Отправка...';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'fetch',
                    },
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    applyErrors(form, payload.errors || {});
                    if (message) {
                        message.textContent = payload.message || 'Не удалось отправить форму.';
                    }
                    window.showToast?.(payload.message || 'Ошибка отправки формы.', 'error');
                    return;
                }

                if (message) {
                    message.textContent = payload.message || 'Успешно.';
                }

                window.showToast?.(payload.message || 'Успешно.', 'success');
                window.location.href = payload.data?.redirect || 'dashboard.php';
            } catch (error) {
                if (message) {
                    message.textContent = 'Не удалось связаться с сервером.';
                }
                window.showToast?.('Не удалось связаться с сервером.', 'error');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        });
    });
}

function validateAuthForm(form, formData) {
    const errors = {};
    const isRegister = form.action.includes('/register.php');
    const email = String(formData.get('email') || '').trim();
    const password = String(formData.get('password') || '');

    if (!email || !email.includes('@')) {
        errors.email = 'Введите корректный email.';
    }

    if (!password) {
        errors.password = 'Введите пароль.';
    }

    if (isRegister) {
        const name = String(formData.get('name') || '').trim();
        const passwordConfirm = String(formData.get('password_confirm') || '');

        if (name.length < 2) {
            errors.name = 'Введите имя не короче 2 символов.';
        }

        if (password.length < 8) {
            errors.password = 'Пароль должен содержать минимум 8 символов.';
        }

        if (password !== passwordConfirm) {
            errors.password_confirm = 'Пароли не совпадают.';
        }
    }

    return errors;
}

function clearFormErrors(form) {
    form.querySelectorAll('[data-error-for]').forEach((item) => {
        item.textContent = '';
    });

    const message = form.querySelector('[data-form-message]');
    if (message) {
        message.textContent = '';
    }
}

function applyErrors(form, errors) {
    Object.entries(errors).forEach(([field, text]) => {
        const target = form.querySelector(`[data-error-for="${field}"]`);
        if (target) {
            target.textContent = text;
        }
    });
}
