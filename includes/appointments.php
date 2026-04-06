<?php
declare(strict_types=1);

function all_appointments(): array
{
    return read_json_storage(APPOINTMENTS_FILE);
}

function save_appointments(array $appointments): bool
{
    return write_json_storage(APPOINTMENTS_FILE, $appointments);
}

function appointments_for_user(int $userId): array
{
    $appointments = array_values(
        array_filter(
            all_appointments(),
            static fn(array $appointment): bool => (int) ($appointment['user_id'] ?? 0) === $userId
        )
    );

    return sort_appointments($appointments, 'desc');
}

function create_user_appointment(int $userId, array $data): ?array
{
    $appointments = all_appointments();
    $appointments[] = [
        'id' => next_storage_id($appointments),
        'user_id' => $userId,
        'service' => $data['service'],
        'doctor' => $data['doctor'],
        'appointment_date' => $data['appointment_date'],
        'appointment_time' => $data['appointment_time'],
        'comment' => $data['comment'],
        'status' => $data['status'] ?? 'Ожидает подтверждения',
        'created_at' => date(DATE_ATOM),
    ];

    if (!save_appointments($appointments)) {
        return null;
    }

    return $appointments[array_key_last($appointments)] ?? null;
}

function update_user_appointment(int $appointmentId, int $userId, array $data): ?array
{
    $appointments = all_appointments();

    foreach ($appointments as $index => $appointment) {
        if ((int) ($appointment['id'] ?? 0) !== $appointmentId || (int) ($appointment['user_id'] ?? 0) !== $userId) {
            continue;
        }

        $appointments[$index] = array_merge(
            $appointment,
            [
                'service' => $data['service'],
                'doctor' => $data['doctor'],
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'comment' => $data['comment'],
            ]
        );

        if (!save_appointments($appointments)) {
            return null;
        }

        return $appointments[$index];
    }

    return null;
}

function delete_user_appointment(int $appointmentId, int $userId): bool
{
    $appointments = all_appointments();
    $deleted = false;

    $filtered = array_values(
        array_filter(
            $appointments,
            static function (array $appointment) use ($appointmentId, $userId, &$deleted): bool {
                $matched = (int) ($appointment['id'] ?? 0) === $appointmentId
                    && (int) ($appointment['user_id'] ?? 0) === $userId;

                if ($matched) {
                    $deleted = true;
                    return false;
                }

                return true;
            }
        )
    );

    if (!$deleted) {
        return false;
    }

    return save_appointments($filtered);
}

function appointment_status_class(string $status): string
{
    return match ($status) {
        'Подтверждена' => 'success',
        'Завершена' => 'muted',
        'Отменена' => 'danger',
        default => 'pending',
    };
}

function present_appointment(array $appointment): array
{
    return [
        'id' => (int) ($appointment['id'] ?? 0),
        'user_id' => (int) ($appointment['user_id'] ?? 0),
        'service' => (string) ($appointment['service'] ?? ''),
        'doctor' => (string) ($appointment['doctor'] ?? ''),
        'appointment_date' => (string) ($appointment['appointment_date'] ?? ''),
        'appointment_time' => (string) ($appointment['appointment_time'] ?? ''),
        'comment' => (string) ($appointment['comment'] ?? ''),
        'status' => (string) ($appointment['status'] ?? 'Ожидает подтверждения'),
        'created_at' => (string) ($appointment['created_at'] ?? ''),
        'date_label' => format_date_ru((string) ($appointment['appointment_date'] ?? '')),
        'time_label' => format_time_ru((string) ($appointment['appointment_time'] ?? '')),
        'datetime_label' => format_appointment_datetime($appointment),
        'status_class' => appointment_status_class((string) ($appointment['status'] ?? 'Ожидает подтверждения')),
    ];
}

function render_appointment_card(array $appointment): string
{
    $view = present_appointment($appointment);

    ob_start();
    ?>
    <article class="appointment-card" data-id="<?= e((string) $view['id']) ?>">
        <div class="appointment-card__top">
            <div>
                <p class="appointment-card__eyebrow">Визит #<?= e((string) $view['id']) ?></p>
                <h3><?= e($view['service']) ?></h3>
            </div>
            <span class="status-badge status-badge--<?= e($view['status_class']) ?>"><?= e($view['status']) ?></span>
        </div>
        <div class="appointment-card__meta">
            <span><?= e($view['doctor']) ?></span>
            <span><?= e($view['date_label']) ?></span>
            <span><?= e($view['time_label']) ?></span>
        </div>
        <p class="appointment-card__comment"><?= e($view['comment'] !== '' ? $view['comment'] : 'Комментарий не указан.') ?></p>
        <div class="appointment-card__actions">
            <button type="button" class="button button--ghost button--small" data-action="edit" data-id="<?= e((string) $view['id']) ?>">Редактировать</button>
            <button type="button" class="button button--outline-danger button--small" data-action="delete" data-id="<?= e((string) $view['id']) ?>">Удалить</button>
        </div>
    </article>
    <?php

    return (string) ob_get_clean();
}
