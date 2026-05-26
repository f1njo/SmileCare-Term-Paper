document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-admin-app]');
    const dataNode = document.getElementById('admin-appointments-data');
    if (!root || !dataNode) {
        return;
    }

    const items = new Map();
    JSON.parse(dataNode.textContent || '[]').forEach((item) => items.set(String(item.id), item));

    root.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-status-form]');
        if (!form) {
            return;
        }

        event.preventDefault();
        const button = form.querySelector('[type="submit"]');
        if (button) {
            button.disabled = true;
        }

        try {
            const payload = await sendAdminRequest(root.dataset.statusUrl, new FormData(form));
            const appointment = payload.data?.appointment;
            if (!appointment) {
                throw new Error('Сервер не вернул обновлённую запись.');
            }

            items.set(String(appointment.id), appointment);
            updateCardStatus(form.closest('.appointment-card'), appointment);
            syncStats(items);
            window.showToast?.(payload.message || 'Статус записи обновлён.', 'success');
        } catch (error) {
            window.showToast?.(error.message || 'Не удалось изменить статус записи.', 'error');
        } finally {
            if (button) {
                button.disabled = false;
            }
        }
    });
});

function updateCardStatus(card, appointment) {
    if (!card) {
        return;
    }

    const badge = card.querySelector('[data-status-badge]');
    if (!badge) {
        return;
    }

    badge.textContent = appointment.status;
    badge.className = `status-badge status-badge--${appointment.status_class || 'pending'}`;
}

function syncStats(items) {
    const appointments = Array.from(items.values());
    const total = document.querySelector('[data-admin-total]');
    const pending = document.querySelector('[data-admin-pending]');
    const confirmed = document.querySelector('[data-admin-confirmed]');

    if (total) {
        total.textContent = String(appointments.length);
    }

    if (pending) {
        pending.textContent = String(appointments.filter((item) => item.status === 'Ожидает подтверждения').length);
    }

    if (confirmed) {
        confirmed.textContent = String(appointments.filter((item) => item.status === 'Подтверждена').length);
    }
}

async function sendAdminRequest(url, body) {
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
        throw new Error(payload.message || 'Ошибка запроса.');
    }

    return payload;
}
