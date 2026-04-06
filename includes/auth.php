<?php
declare(strict_types=1);

function current_user(): ?array
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
}

function current_user_id(): ?int
{
    return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
}

function is_authenticated(): bool
{
    return current_user_id() !== null;
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'name' => (string) $user['name'],
        'email' => (string) $user['email'],
        'created_at' => (string) ($user['created_at'] ?? ''),
    ];
}

function logout_user(): void
{
    unset($_SESSION['user'], $_SESSION['csrf_token']);
    session_regenerate_id(true);
}

function require_auth_page(): void
{
    if (!is_authenticated()) {
        set_flash('warning', 'Сначала войдите в личный кабинет.');
        redirect_to('login.php');
    }
}

function require_auth_api(): void
{
    if (!is_authenticated()) {
        json_error('Требуется авторизация.', 401);
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function ensure_csrf_token(array $input): void
{
    $receivedToken = (string) ($input['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));
    $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');

    if ($receivedToken === '' || $sessionToken === '' || !hash_equals($sessionToken, $receivedToken)) {
        json_error('Сессия формы устарела. Обновите страницу и повторите попытку.', 419);
    }
}

function all_users(): array
{
    return read_json_storage(USERS_FILE);
}

function save_users(array $users): bool
{
    return write_json_storage(USERS_FILE, $users);
}

function find_user_by_email(string $email): ?array
{
    foreach (all_users() as $user) {
        if (($user['email'] ?? '') === $email) {
            return $user;
        }
    }

    return null;
}

function find_user_by_id(int $id): ?array
{
    foreach (all_users() as $user) {
        if ((int) ($user['id'] ?? 0) === $id) {
            return $user;
        }
    }

    return null;
}

function create_user(string $name, string $email, string $password): ?array
{
    $users = all_users();
    $users[] = [
        'id' => next_storage_id($users),
        'name' => $name,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date(DATE_ATOM),
    ];

    if (!save_users($users)) {
        return null;
    }

    return $users[array_key_last($users)] ?? null;
}

function refresh_session_user(): void
{
    if (!is_authenticated()) {
        return;
    }

    $user = find_user_by_id(current_user_id() ?? 0);
    if ($user === null) {
        logout_user();
        return;
    }

    $_SESSION['user']['name'] = (string) $user['name'];
    $_SESSION['user']['email'] = (string) $user['email'];
    $_SESSION['user']['created_at'] = (string) ($user['created_at'] ?? '');
}
