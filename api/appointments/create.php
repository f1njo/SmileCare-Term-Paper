<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';

require_auth_api();
require_http_method('POST');

$input = get_request_data();
ensure_csrf_token($input);

$validation = validate_appointment_input($input);
if ($validation['errors'] !== []) {
    json_error('Проверьте поля формы записи.', 422, $validation['errors']);
}

$appointment = create_user_appointment(current_user_id() ?? 0, $validation['values']);
if ($appointment === null) {
    json_error('Не удалось сохранить запись.', 500);
}

json_success('Запись успешно создана.', [
    'appointment' => present_appointment($appointment),
]);
