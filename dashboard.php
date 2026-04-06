<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

require_auth_page();

$user = current_user();
$appointments = appointments_for_user((int) $user['id']);
$totalAppointments = count($appointments);
$upcomingAppointments = array_values(
    array_filter(
        $appointments,
        static fn(array $appointment): bool => appointment_timestamp($appointment) >= time()
    )
);
$nextAppointment = next_upcoming_appointment($appointments);
$recentAppointments = array_slice($appointments, 0, 3);

$pageTitle = 'Личный кабинет | SmileCare';
$pageDescription = 'Личный кабинет пациента SmileCare с краткой сводкой и последними записями.';
$pageClass = 'dashboard-page';

include __DIR__ . '/includes/header.php';
?>
<main class="dashboard-main">
    <section class="dashboard-hero">
        <div class="container dashboard-hero__grid">
            <div>
                <p class="section-eyebrow">Добро пожаловать</p>
                <h1><?= e((string) $user['name']) ?>, ваш кабинет готов к работе.</h1>
                <p class="hero-text">Здесь собрана личная информация пациента, ближайшие визиты и быстрый переход к управлению записями.</p>
                <div class="hero-actions">
                    <a href="appointments.php" class="button button--primary">Управлять записями</a>
                    <a href="index.php#prices" class="button button--ghost">Посмотреть цены</a>
                </div>
            </div>
            <div class="dashboard-hero__cards">
                <article class="dashboard-stat">
                    <span>Всего записей</span>
                    <strong><?= e((string) $totalAppointments) ?></strong>
                </article>
                <article class="dashboard-stat">
                    <span>Будущих визитов</span>
                    <strong><?= e((string) count($upcomingAppointments)) ?></strong>
                </article>
                <article class="dashboard-stat">
                    <span>Аккаунт создан</span>
                    <strong><?= e(date('d.m.Y', strtotime((string) ($user['created_at'] ?? 'now')))) ?></strong>
                </article>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="container dashboard-grid">
            <article class="panel-card">
                <p class="section-eyebrow">Профиль</p>
                <h2>Данные пациента</h2>
                <dl class="profile-list">
                    <div>
                        <dt>Имя</dt>
                        <dd><?= e((string) $user['name']) ?></dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd><?= e((string) $user['email']) ?></dd>
                    </div>
                    <div>
                        <dt>Формат хранения</dt>
                        <dd>Персональный кабинет пациента</dd>
                    </div>
                </dl>
            </article>

            <article class="panel-card">
                <p class="section-eyebrow">Следующий визит</p>
                <h2><?= $nextAppointment ? 'Ближайшая запись уже в системе' : 'Пока нет будущих записей' ?></h2>
                <?php if ($nextAppointment): $next = present_appointment($nextAppointment); ?>
                    <div class="next-appointment">
                        <strong><?= e($next['service']) ?></strong>
                        <p><?= e($next['doctor']) ?></p>
                        <p><?= e($next['datetime_label']) ?></p>
                        <span class="status-badge status-badge--<?= e($next['status_class']) ?>"><?= e($next['status']) ?></span>
                    </div>
                <?php else: ?>
                    <p class="empty-copy">Создайте первую запись на приём на отдельной функциональной странице.</p>
                    <a href="appointments.php" class="button button--primary">Перейти к записям</a>
                <?php endif; ?>
            </article>
        </div>
    </section>

    <section class="section-block section-block--muted">
        <div class="container panel-card">
            <div class="panel-card__header">
                <div>
                    <p class="section-eyebrow">История</p>
                    <h2>Последние записи</h2>
                </div>
                <a href="appointments.php" class="button button--ghost button--small">Все записи</a>
            </div>

            <?php if ($recentAppointments === []): ?>
                <div class="empty-state">
                    <h3>У вас пока нет записей</h3>
                    <p>Создайте первую запись через страницу управления визитами и выберите удобные дату и время приёма.</p>
                </div>
            <?php else: ?>
                <div class="dashboard-list">
                    <?php foreach ($recentAppointments as $appointment): $view = present_appointment($appointment); ?>
                        <article class="dashboard-list__item">
                            <div>
                                <h3><?= e($view['service']) ?></h3>
                                <p><?= e($view['doctor']) ?> · <?= e($view['datetime_label']) ?></p>
                            </div>
                            <span class="status-badge status-badge--<?= e($view['status_class']) ?>"><?= e($view['status']) ?></span>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
