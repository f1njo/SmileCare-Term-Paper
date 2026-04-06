<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';

require_auth_api();
require_http_method('POST');

$input = get_request_data();
ensure_csrf_token($input);

$appointmentId = (int) ($input['id'] ?? 0);
if ($appointmentId <= 0) {
    json_error('Не удалось определить запись для редактирования.', 422, [
        'id' => 'Некорректный идентификатор записи.',
    ]);
}

$validation = validate_appointment_input($input);
if ($validation['errors'] !== []) {
    json_error('Проверьте поля формы записи.', 422, $validation['errors']);
}

$existing = null;
foreach (appointments_for_user(current_user_id() ?? 0) as $appointment) {
    if ((int) ($appointment['id'] ?? 0) === $appointmentId) {
        $existing = $appointment;
        break;
    }
}

if ($existing === null) {
    json_error('Запись не найдена или недоступна.', 404);
}

$updated = update_user_appointment($appointmentId, current_user_id() ?? 0, $validation['values']);
if ($updated === null) {
    json_error('Не удалось обновить запись.', 500);
}

json_success('Запись успешно обновлена.', [
    'appointment' => present_appointment($updated),
]);
