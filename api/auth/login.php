<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';

require_http_method('POST');

$input = get_request_data();
ensure_csrf_token($input);

$validation = validate_login_input($input);
if ($validation['errors'] !== []) {
    json_error('Проверьте корректность введённых данных.', 422, $validation['errors']);
}

$values = $validation['values'];
$user = find_user_by_email($values['email']);

if ($user === null || !password_verify($values['password'], (string) ($user['password_hash'] ?? ''))) {
    json_error('Неверный email или пароль.', 422, [
        'email' => 'Пара email/пароль не найдена.',
    ]);
}

login_user($user);

json_success('Вход выполнен.', [
    'redirect' => 'dashboard.php',
]);
