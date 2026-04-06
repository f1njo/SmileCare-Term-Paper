<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (is_authenticated()) {
    redirect_to('dashboard.php');
}

$pageTitle = 'Вход | SmileCare';
$pageDescription = 'Авторизация пациента в личный кабинет SmileCare.';
$pageClass = 'auth-page';
$pageScripts = ['auth.js'];

include __DIR__ . '/includes/header.php';
?>
<main class="auth-main">
    <div class="container auth-layout">
        <section class="auth-aside">
            <p class="section-eyebrow">Личный кабинет пациента</p>
            <h1>Войдите, чтобы управлять визитами онлайн.</h1>
            <p>
                В личном кабинете доступны история визитов, онлайн-запись и управление предстоящими посещениями.
            </p>
            <div class="auth-benefits">
                <div class="info-chip">
                    <strong>Сессии</strong>
                    <span>доступ только после входа</span>
                </div>
                <div class="info-chip">
                    <strong>Онлайн-запись</strong>
                    <span>быстрый доступ к визитам и статусам</span>
                </div>
                <div class="info-chip">
                    <strong>Удобно</strong>
                    <span>все действия доступны в одном кабинете</span>
                </div>
            </div>
        </section>

        <section class="auth-card">
            <div class="auth-switch">
                <a href="login.php" class="auth-switch__link is-active">Вход</a>
                <a href="register.php" class="auth-switch__link">Регистрация</a>
            </div>

            <form class="auth-form" method="post" action="api/auth/login.php" data-auth-form novalidate>
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <label class="field">
                    <span>Email</span>
                    <input type="email" name="email" placeholder="patient@example.com" autocomplete="email" required>
                    <small class="field-error" data-error-for="email"></small>
                </label>

                <label class="field">
                    <span>Пароль</span>
                    <div class="password-field">
                        <input type="password" name="password" placeholder="Минимум 8 символов" autocomplete="current-password" required>
                        <button type="button" class="password-toggle" data-password-toggle>Показать</button>
                    </div>
                    <small class="field-error" data-error-for="password"></small>
                </label>

                <div class="form-message" data-form-message></div>
                <button type="submit" class="button button--primary button--large button--block">Войти в кабинет</button>
            </form>
        </section>
    </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
