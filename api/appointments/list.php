<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';

require_auth_api();

$appointments = array_map('present_appointment', appointments_for_user(current_user_id() ?? 0));

json_success('Список записей загружен.', [
    'appointments' => $appointments,
]);
