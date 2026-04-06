<?php
declare(strict_types=1);

$pageTitle = $pageTitle ?? APP_NAME;
$pageDescription = $pageDescription ?? 'Современная стоматологическая клиника с цифровым личным кабинетом пациента.';
$pageClass = $pageClass ?? '';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/reset.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/variables.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>">
</head>
<body class="<?= e($pageClass) ?>">
<div class="site-shell">
    <header class="site-header">
        <div class="container header-shell">
            <a href="index.php" class="brand" aria-label="На главную SmileCare">
                <span class="brand__mark">SC</span>
                <span class="brand__text">
                    <strong>SmileCare</strong>
                    <small>стоматология нового уровня</small>
                </span>
            </a>

            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="site-nav" id="site-nav" data-site-nav>
                <a href="index.php#services">Услуги</a>
                <a href="index.php#doctors">Врачи</a>
                <a href="index.php#prices">Цены</a>
                <a href="index.php#faq">FAQ</a>
                <a href="index.php#contacts">Контакты</a>
            </nav>

            <div class="header-actions">
                <?php if ($user !== null): ?>
                    <span class="user-pill"><?= e($user['name']) ?></span>
                    <a href="dashboard.php" class="button button--ghost">Кабинет</a>
                    <a href="appointments.php" class="button button--primary">Записи</a>
                    <a href="logout.php" class="button button--ghost">Выйти</a>
                <?php else: ?>
                    <a href="login.php" class="button button--ghost">Войти</a>
                    <a href="register.php" class="button button--primary">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
