<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Krasnoyarsk');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);

    session_start();
}

define('APP_NAME', 'SmileCare');
define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'data');
define('USERS_FILE', DATA_PATH . DIRECTORY_SEPARATOR . 'users.json');
define('APPOINTMENTS_FILE', DATA_PATH . DIRECTORY_SEPARATOR . 'appointments.json');

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/json_storage.php';
require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/appointments.php';

ensure_storage_file(USERS_FILE);
ensure_storage_file(APPOINTMENTS_FILE);
refresh_session_user();
