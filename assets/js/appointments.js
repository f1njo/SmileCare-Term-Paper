document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-appointments-app]');
    if (!root) {
        return;
    }

    const form = document.getElementById('appointment-form');
    const list = document.getElementById('appointments-list');
    const emptyState = document.getElementById('appointments-empty');
    const formTitle = document.querySelector('[data-form-title]');
    const submitLabel = document.querySelector('[data-submit-label]');
    const cancelEditButton = document.querySelector('[data-cancel-edit]');
    const totalStat = document.querySelector('[data-stat-total]');
    const pendingStat = document.querySelector('[data-stat-pending]');
    const initialDataNode = document.getElementById('appointments-data');

    if (!form || !list || !emptyState || !initialDataNode) {
        return;
    }

    const items = new Map();
    const initialItems = JSON.parse(initialDataNode.textContent || '[]');
    initialItems.forEach((item) => items.set(String(item.id), item));

    setMinDate(form);
    syncStats();

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearFormErrors(form);

        const isEdit = Boolean(form.elements.id.value);
        const formData = new FormData(form);
        const clientErrors = validateAppointmentForm(formData);
        if (Object.keys(clientErrors).length > 0) {
            applyFormErrors(form, clientErrors);
            return;
        }

        const endpoint = isEdit ? form.dataset.updateUrl : form.dataset.createUrl;
        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }

        try {
            const payload = await sendRequest(endpoint, formData);
            const appointment = payload.data?.appointment;
            if (!appointment) {
                throw new Error('Пустой ответ сервера');
            }

            items.set(String(appointment.id), appointment);
            upsertCard(appointment);
            resetFormState();
            syncStats();
            window.showToast?.(payload.message || 'Запись сохранена.', 'success');
        } catch (error) {
            handleApiError(form, error);
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    });

    list.addEventListener('click', async (event) => {
        const button = event.target.closest('button[data-action]');
        if (!button) {
            return;
        }

        const action = button.dataset.action;
        const id = String(button.dataset.id || '');
        if (!id || !items.has(id)) {
            return;
        }

        if (action === 'edit') {
            fillForm(items.get(id));
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }

        if (action === 'delete') {
            const shouldDelete = window.confirm('Удалить запись на приём?');
            if (!shouldDelete) {
                return;
            }

            const formData = new FormData();
            formData.set('id', id);
            formData.set('csrf_token', String(form.elements.csrf_token.value));

            button.disabled = true;

            try {
                const payload = await sendRequest(root.dataset.deleteUrl, formData);
                items.delete(id);
                removeCard(id);
                syncStats();
                resetFormState();
                window.showToast?.(payload.message || 'Запись удалена.', 'success');
            } catch (error) {
                window.showToast?.(error.message || 'Не удалось удалить запись.', 'error');
            } finally {
                button.disabled = false;
            }
        }
    });

    cancelEditButton?.addEventListener('click', () => {
        resetFormState();
    });

    function setMinDate(targetForm) {
        const dateInput = targetForm.elements.appointment_date;
        if (!dateInput) {
            return;
        }

        const today = new Date();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        dateInput.min = `${today.getFullYear()}-${month}-${day}`;
    }

    function fillForm(item) {
        form.elements.id.value = item.id;
        form.elements.service.value = item.service;
        form.elements.doctor.value = item.doctor;
        form.elements.appointment_date.value = item.appointment_date;
        form.elements.appointment_time.value = item.appointment_time;
        form.elements.comment.value = item.comment || '';

        if (formTitle) {
            formTitle.textContent = `Редактирование записи #${item.id}`;
        }

        if (submitLabel) {
            submitLabel.textContent = 'Сохранить изменения';
        }

        if (cancelEditButton) {
            cancelEditButton.hidden = false;
        }
    }

    function resetFormState() {
        form.reset();
        form.elements.id.value = '';
        setMinDate(form);
        clearFormErrors(form);

        if (formTitle) {
            formTitle.textContent = 'Создать новую запись';
        }

        if (submitLabel) {
            submitLabel.textContent = 'Создать запись';
        }

        if (cancelEditButton) {
            cancelEditButton.hidden = true;
        }
    }

    function upsertCard(item) {
        const selector = `.appointment-card[data-id="${CSS.escape(String(item.id))}"]`;
        const existing = list.querySelector(selector);
        const card = buildCard(item);

        if (existing) {
            existing.replaceWith(card);
        } else {
            list.prepend(card);
        }

        list.classList.remove('is-hidden');
        emptyState.classList.add('is-hidden');
    }

    function removeCard(id) {
        const card = list.querySelector(`.appointment-card[data-id="${CSS.escape(String(id))}"]`);
        card?.remove();

        if (items.size === 0) {
            list.classList.add('is-hidden');
            emptyState.classList.remove('is-hidden');
        }
    }

    function buildCard(item) {
        const article = document.createElement('article');
        article.className = 'appointment-card';
        article.dataset.id = item.id;

        const top = document.createElement('div');
        top.className = 'appointment-card__top';

        const headingWrap = document.createElement('div');
        const eyebrow = document.createElement('p');
        eyebrow.className = 'appointment-card__eyebrow';
        eyebrow.textContent = `Визит #${item.id}`;
        const title = document.createElement('h3');
        title.textContent = item.service;
        headingWrap.append(eyebrow, title);

        const status = document.createElement('span');
        status.className = `status-badge status-badge--${item.status_class || 'pending'}`;
        status.textContent = item.status;
        top.append(headingWrap, status);

        const meta = document.createElement('div');
        meta.className = 'appointment-card__meta';
        [item.doctor, item.date_label, item.time_label].forEach((value) => {
            const badge = document.createElement('span');
            badge.textContent = value;
            meta.appendChild(badge);
        });

        const comment = document.createElement('p');
        comment.className = 'appointment-card__comment';
        comment.textContent = item.comment || 'Комментарий не указан.';

        const actions = document.createElement('div');
        actions.className = 'appointment-card__actions';

        const editButton = document.createElement('button');
        editButton.type = 'button';
        editButton.className = 'button button--ghost button--small';
        editButton.dataset.action = 'edit';
        editButton.dataset.id = item.id;
        editButton.textContent = 'Редактировать';

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'button button--outline-danger button--small';
        deleteButton.dataset.action = 'delete';
        deleteButton.dataset.id = item.id;
        deleteButton.textContent = 'Удалить';

        actions.append(editButton, deleteButton);
        article.append(top, meta, comment, actions);

        return article;
    }

    function syncStats() {
        if (totalStat) {
            totalStat.textContent = String(items.size);
        }

        if (pendingStat) {
            const pendingCount = Array.from(items.values()).filter((item) => item.status === 'Ожидает подтверждения').length;
            pendingStat.textContent = String(pendingCount);
        }
    }
});

function validateAppointmentForm(formData) {
    const errors = {};

    if (!String(formData.get('service') || '').trim()) {
        errors.service = 'Выберите услугу.';
    }

    if (!String(formData.get('doctor') || '').trim()) {
        errors.doctor = 'Выберите врача.';
    }

    if (!String(formData.get('appointment_date') || '').trim()) {
        errors.appointment_date = 'Укажите дату.';
    }

    if (!String(formData.get('appointment_time') || '').trim()) {
        errors.appointment_time = 'Укажите время.';
    }

    const comment = String(formData.get('comment') || '').trim();
    if (comment.length > 500) {
        errors.comment = 'Комментарий не должен превышать 500 символов.';
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

function applyFormErrors(form, errors) {
    Object.entries(errors).forEach(([field, text]) => {
        const target = form.querySelector(`[data-error-for="${field}"]`);
        if (target) {
            target.textContent = text;
        }
    });
}

function handleApiError(form, error) {
    const message = form.querySelector('[data-form-message]');

    if (error && error.errors) {
        applyFormErrors(form, error.errors);
    }

    if (message) {
        message.textContent = error.message || 'Не удалось выполнить действие.';
    }

    window.showToast?.(error.message || 'Не удалось выполнить действие.', 'error');
}

async function sendRequest(url, body) {
    const response = await fetch(url, {
        method: 'POST',
        body,
        headers: {
            'X-Requested-With': 'fetch',
        },
    });

    let payload = {};
    try {
        payload = await response.json();
    } catch (error) {
        throw new Error('Сервер вернул некорректный ответ.');
    }

    if (!response.ok || !payload.success) {
        const apiError = new Error(payload.message || 'Ошибка запроса.');
        apiError.errors = payload.errors || {};
        throw apiError;
    }

    return payload;
}
