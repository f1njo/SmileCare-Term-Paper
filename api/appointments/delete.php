<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';

require_auth_api();
require_http_method('POST');

$input = get_request_data();
ensure_csrf_token($input);

$appointmentId = (int) ($input['id'] ?? 0);
if ($appointmentId <= 0) {
    json_error('Не удалось определить запись для удаления.', 422, [
        'id' => 'Некорректный идентификатор записи.',
    ]);
}

$allowed = false;
foreach (appointments_for_user(current_user_id() ?? 0) as $appointment) {
    if ((int) ($appointment['id'] ?? 0) === $appointmentId) {
        $allowed = true;
        break;
    }
}

if (!$allowed) {
    json_error('Запись не найдена или не принадлежит текущему пользователю.', 404);
}

if (!delete_user_appointment($appointmentId, current_user_id() ?? 0)) {
    json_error('Не удалось удалить запись.', 500);
}

json_success('Запись удалена.', [
    'id' => $appointmentId,
]);
