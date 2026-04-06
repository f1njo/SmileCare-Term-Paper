<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (is_authenticated()) {
    redirect_to('dashboard.php');
}

$pageTitle = 'Регистрация | SmileCare';
$pageDescription = 'Регистрация нового пациента в системе SmileCare.';
$pageClass = 'auth-page';
$pageScripts = ['auth.js'];

include __DIR__ . '/includes/header.php';
?>
<main class="auth-main">
    <div class="container auth-layout">
        <section class="auth-aside">
            <p class="section-eyebrow">Регистрация</p>
            <h1>Создайте аккаунт и управляйте записью самостоятельно.</h1>
            <p>
                Регистрация открывает доступ к личному кабинету, истории визитов и самостоятельной записи на приём.
            </p>
            <div class="auth-benefits">
                <div class="info-chip">
                    <strong>Безопасно</strong>
                    <span>личные данные пациента защищены</span>
                </div>
                <div class="info-chip">
                    <strong>Просто</strong>
                    <span>понятная форма и серверная валидация</span>
                </div>
                <div class="info-chip">
                    <strong>Удобно</strong>
                    <span>после регистрации сразу открывается кабинет</span>
                </div>
            </div>
        </section>

        <section class="auth-card">
            <div class="auth-switch">
                <a href="login.php" class="auth-switch__link">Вход</a>
                <a href="register.php" class="auth-switch__link is-active">Регистрация</a>
            </div>

            <form class="auth-form" method="post" action="api/auth/register.php" data-auth-form novalidate>
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <label class="field">
                    <span>Имя</span>
                    <input type="text" name="name" placeholder="Анна Иванова" autocomplete="name" required>
                    <small class="field-error" data-error-for="name"></small>
                </label>

                <label class="field">
                    <span>Email</span>
                    <input type="email" name="email" placeholder="patient@example.com" autocomplete="email" required>
                    <small class="field-error" data-error-for="email"></small>
                </label>

                <label class="field">
                    <span>Пароль</span>
                    <div class="password-field">
                        <input type="password" name="password" placeholder="Минимум 8 символов" autocomplete="new-password" required>
                        <button type="button" class="password-toggle" data-password-toggle>Показать</button>
                    </div>
                    <small class="field-error" data-error-for="password"></small>
                </label>

                <label class="field">
                    <span>Повторите пароль</span>
                    <div class="password-field">
                        <input type="password" name="password_confirm" placeholder="Повторите пароль" autocomplete="new-password" required>
                        <button type="button" class="password-toggle" data-password-toggle>Показать</button>
                    </div>
                    <small class="field-error" data-error-for="password_confirm"></small>
                </label>

                <div class="form-message" data-form-message></div>
                <button type="submit" class="button button--primary button--large button--block">Создать аккаунт</button>
            </form>
        </section>
    </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
