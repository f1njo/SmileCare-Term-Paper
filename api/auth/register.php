<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';

require_http_method('POST');

$input = get_request_data();
ensure_csrf_token($input);

$validation = validate_registration_input($input);
if ($validation['errors'] !== []) {
    json_error('Форма содержит ошибки.', 422, $validation['errors']);
}

$values = $validation['values'];

if (find_user_by_email($values['email']) !== null) {
    json_error('Пользователь с таким email уже зарегистрирован.', 422, [
        'email' => 'Этот email уже используется.',
    ]);
}

$user = create_user($values['name'], $values['email'], $values['password']);
if ($user === null) {
    json_error('Не удалось сохранить пользователя.', 500);
}

login_user($user);

json_success('Аккаунт успешно создан.', [
    'redirect' => 'dashboard.php',
]);
