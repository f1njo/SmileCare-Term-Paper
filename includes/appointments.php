<?php
declare(strict_types=1);

function appointment_row_sql(): string
{
    return 'SELECT
            a.id,
            a.user_id,
            a.service_id,
            a.doctor_id,
            a.status_id,
            s.name AS service,
            u_doctor.name AS doctor,
            st.name AS status,
            a.appointment_date,
            a.appointment_time,
            a.comment,
            a.created_at
        FROM appointments a
        JOIN services s ON s.id = a.service_id
        JOIN doctors d ON d.id = a.doctor_id
        JOIN users u_doctor ON u_doctor.id = d.user_id
        JOIN appointment_statuses st ON st.id = a.status_id';
}

function all_appointments(): array
{
    $stmt = db()->query(appointment_row_sql() . ' ORDER BY a.appointment_date DESC, a.appointment_time DESC, a.id DESC');

    return $stmt->fetchAll();
}

function appointments_for_user(int $userId): array
{
    $stmt = db()->prepare(
        appointment_row_sql() . '
        WHERE a.user_id = :user_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC, a.id DESC'
    );
    $stmt->execute(['user_id' => $userId]);

    return $stmt->fetchAll();
}

function appointments_for_admin(): array
{
    $stmt = db()->query(
        'SELECT
            a.id,
            a.user_id,
            a.service_id,
            a.doctor_id,
            a.status_id,
            s.name AS service,
            u_doctor.name AS doctor,
            st.name AS status,
            u_patient.name AS patient_name,
            u_patient.email AS patient_email,
            a.appointment_date,
            a.appointment_time,
            a.comment,
            a.created_at
        FROM appointments a
        JOIN services s ON s.id = a.service_id
        JOIN doctors d ON d.id = a.doctor_id
        JOIN users u_doctor ON u_doctor.id = d.user_id
        JOIN users u_patient ON u_patient.id = a.user_id
        JOIN appointment_statuses st ON st.id = a.status_id
        ORDER BY a.appointment_date ASC, a.appointment_time ASC, a.id ASC'
    );

    return $stmt->fetchAll();
}

function appointment_for_admin(int $appointmentId): ?array
{
    foreach (appointments_for_admin() as $appointment) {
        if ((int) ($appointment['id'] ?? 0) === $appointmentId) {
            return $appointment;
        }
    }

    return null;
}

function appointment_for_user(int $appointmentId, int $userId): ?array
{
    $stmt = db()->prepare(
        appointment_row_sql() . '
        WHERE a.id = :id AND a.user_id = :user_id
        LIMIT 1'
    );
    $stmt->execute([
        'id' => $appointmentId,
        'user_id' => $userId,
    ]);
    $appointment = $stmt->fetch();

    return $appointment ?: null;
}

function service_id_by_name(string $service): ?int
{
    $stmt = db()->prepare('SELECT id FROM services WHERE name = :name LIMIT 1');
    $stmt->execute(['name' => $service]);
    $id = $stmt->fetchColumn();

    return $id !== false ? (int) $id : null;
}

function doctor_id_by_name(string $doctor): ?int
{
    $stmt = db()->prepare(
        'SELECT d.id
         FROM doctors d
         JOIN users u ON u.id = d.user_id
         WHERE u.name = :name
         LIMIT 1'
    );
    $stmt->execute(['name' => $doctor]);
    $id = $stmt->fetchColumn();

    return $id !== false ? (int) $id : null;
}

function status_id_by_name(string $status): ?int
{
    $stmt = db()->prepare('SELECT id FROM appointment_statuses WHERE name = :name LIMIT 1');
    $stmt->execute(['name' => $status]);
    $id = $stmt->fetchColumn();

    return $id !== false ? (int) $id : null;
}

function create_user_appointment(int $userId, array $data): ?array
{
    $serviceId = service_id_by_name((string) $data['service']);
    $doctorId = doctor_id_by_name((string) $data['doctor']);
    $statusId = status_id_by_name((string) ($data['status'] ?? 'Ожидает подтверждения'));

    if ($serviceId === null || $doctorId === null || $statusId === null) {
        return null;
    }

    $stmt = db()->prepare(
        'INSERT INTO appointments
            (user_id, service_id, doctor_id, status_id, appointment_date, appointment_time, comment)
         VALUES
            (:user_id, :service_id, :doctor_id, :status_id, :appointment_date, :appointment_time, :comment)'
    );

    $saved = $stmt->execute([
        'user_id' => $userId,
        'service_id' => $serviceId,
        'doctor_id' => $doctorId,
        'status_id' => $statusId,
        'appointment_date' => $data['appointment_date'],
        'appointment_time' => $data['appointment_time'],
        'comment' => $data['comment'],
    ]);

    if (!$saved) {
        return null;
    }

    return appointment_for_user((int) db()->lastInsertId(), $userId);
}

function update_user_appointment(int $appointmentId, int $userId, array $data): ?array
{
    $serviceId = service_id_by_name((string) $data['service']);
    $doctorId = doctor_id_by_name((string) $data['doctor']);

    if ($serviceId === null || $doctorId === null) {
        return null;
    }

    $stmt = db()->prepare(
        'UPDATE appointments
         SET service_id = :service_id,
             doctor_id = :doctor_id,
             appointment_date = :appointment_date,
             appointment_time = :appointment_time,
             comment = :comment
         WHERE id = :id AND user_id = :user_id'
    );

    $stmt->execute([
        'service_id' => $serviceId,
        'doctor_id' => $doctorId,
        'appointment_date' => $data['appointment_date'],
        'appointment_time' => $data['appointment_time'],
        'comment' => $data['comment'],
        'id' => $appointmentId,
        'user_id' => $userId,
    ]);

    if ($stmt->rowCount() === 0 && appointment_for_user($appointmentId, $userId) === null) {
        return null;
    }

    return appointment_for_user($appointmentId, $userId);
}

function delete_user_appointment(int $appointmentId, int $userId): bool
{
    $stmt = db()->prepare('DELETE FROM appointments WHERE id = :id AND user_id = :user_id');
    $stmt->execute([
        'id' => $appointmentId,
        'user_id' => $userId,
    ]);

    return $stmt->rowCount() > 0;
}

function update_appointment_status(int $appointmentId, string $status): ?array
{
    $statusId = status_id_by_name($status);
    if ($statusId === null || appointment_for_admin($appointmentId) === null) {
        return null;
    }

    $stmt = db()->prepare('UPDATE appointments SET status_id = :status_id WHERE id = :id');
    $stmt->execute([
        'status_id' => $statusId,
        'id' => $appointmentId,
    ]);

    return appointment_for_admin($appointmentId);
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
        'patient_name' => (string) ($appointment['patient_name'] ?? ''),
        'patient_email' => (string) ($appointment['patient_email'] ?? ''),
        'appointment_date' => (string) ($appointment['appointment_date'] ?? ''),
        'appointment_time' => substr((string) ($appointment['appointment_time'] ?? ''), 0, 5),
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
