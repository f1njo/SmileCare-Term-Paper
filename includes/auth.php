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

function current_user_role(): string
{
    return (string) ($_SESSION['user']['role'] ?? '');
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
        'role_id' => (int) ($user['role_id'] ?? 0),
        'role' => (string) ($user['role'] ?? ''),
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

function require_admin_page(): void
{
    require_auth_page();

    if (current_user_role() !== 'admin') {
        set_flash('warning', 'Этот раздел доступен только администратору.');
        redirect_to('dashboard.php');
    }
}

function require_admin_api(): void
{
    require_auth_api();

    if (current_user_role() !== 'admin') {
        json_error('Действие доступно только администратору.', 403);
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
    $stmt = db()->query(
        'SELECT u.id, u.role_id, r.name AS role, u.name, u.email, u.password_hash, u.created_at
         FROM users u
         JOIN roles r ON r.id = u.role_id
         ORDER BY u.id'
    );

    return $stmt->fetchAll();
}

function find_user_by_email(string $email): ?array
{
    $stmt = db()->prepare(
        'SELECT u.id, u.role_id, r.name AS role, u.name, u.email, u.password_hash, u.created_at
         FROM users u
         JOIN roles r ON r.id = u.role_id
         WHERE u.email = :email
         LIMIT 1'
    );
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function find_user_by_id(int $id): ?array
{
    $stmt = db()->prepare(
        'SELECT u.id, u.role_id, r.name AS role, u.name, u.email, u.password_hash, u.created_at
         FROM users u
         JOIN roles r ON r.id = u.role_id
         WHERE u.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function role_id_by_name(string $roleName): ?int
{
    $stmt = db()->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
    $stmt->execute(['name' => $roleName]);
    $id = $stmt->fetchColumn();

    return $id !== false ? (int) $id : null;
}

function create_user(string $name, string $email, string $password): ?array
{
    $roleId = role_id_by_name('patient') ?? 1;

    $stmt = db()->prepare(
        'INSERT INTO users (role_id, name, email, password_hash)
         VALUES (:role_id, :name, :email, :password_hash)'
    );

    $saved = $stmt->execute([
        'role_id' => $roleId,
        'name' => $name,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    if (!$saved) {
        return null;
    }

    return find_user_by_id((int) db()->lastInsertId());
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

    $_SESSION['user']['role_id'] = (int) ($user['role_id'] ?? 0);
    $_SESSION['user']['role'] = (string) ($user['role'] ?? '');
    $_SESSION['user']['name'] = (string) $user['name'];
    $_SESSION['user']['email'] = (string) $user['email'];
    $_SESSION['user']['created_at'] = (string) ($user['created_at'] ?? '');
}
