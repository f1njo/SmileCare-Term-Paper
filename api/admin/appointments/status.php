<?php
declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';

require_admin_api();
require_http_method('POST');

$input = get_request_data();
ensure_csrf_token($input);

$appointmentId = (int) ($input['id'] ?? 0);
$status = trim((string) ($input['status'] ?? ''));

if ($appointmentId <= 0) {
    json_error('Запись для изменения статуса не найдена.', 422);
}

if (!in_array($status, appointment_statuses(), true)) {
    json_error('Выберите корректный статус записи.', 422, [
        'status' => 'Недопустимое значение статуса.',
    ]);
}

$appointment = update_appointment_status($appointmentId, $status);
if ($appointment === null) {
    json_error('Не удалось обновить статус записи.', 404);
}

json_success('Статус записи обновлён.', [
    'appointment' => present_appointment($appointment),
]);
