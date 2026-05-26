<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

require_admin_page();

$appointments = appointments_for_admin();
$presentedAppointments = array_map('present_appointment', $appointments);
$statuses = appointment_statuses();
$pendingCount = count(array_filter(
    $appointments,
    static fn(array $appointment): bool => ($appointment['status'] ?? '') === 'Ожидает подтверждения'
));
$confirmedCount = count(array_filter(
    $appointments,
    static fn(array $appointment): bool => ($appointment['status'] ?? '') === 'Подтверждена'
));
$patientsCount = count(array_filter(
    all_users(),
    static fn(array $user): bool => ($user['role'] ?? '') === 'patient'
));

$pageTitle = 'Администрирование | SmileCare';
$pageDescription = 'Панель администратора SmileCare для обработки записей пациентов.';
$pageClass = 'admin-page';
$pageScripts = ['admin.js'];

include __DIR__ . '/includes/header.php';
?>
<main class="admin-main" data-admin-app data-status-url="api/admin/appointments/status.php">
    <section class="dashboard-hero">
        <div class="container dashboard-hero__grid">
            <div>
                <p class="section-eyebrow">Панель администратора</p>
                <h1>Управление записями клиники.</h1>
                <p class="hero-text">
                    Просматривайте заявки пациентов и меняйте их статус после подтверждения,
                    завершения визита или отмены.
                </p>
            </div>
            <div class="admin-stats">
                <article class="dashboard-stat">
                    <span>Всего записей</span>
                    <strong data-admin-total><?= e((string) count($appointments)) ?></strong>
                </article>
                <article class="dashboard-stat">
                    <span>Ожидают решения</span>
                    <strong data-admin-pending><?= e((string) $pendingCount) ?></strong>
                </article>
                <article class="dashboard-stat">
                    <span>Подтверждено</span>
                    <strong data-admin-confirmed><?= e((string) $confirmedCount) ?></strong>
                </article>
                <article class="dashboard-stat">
                    <span>Пациентов</span>
                    <strong><?= e((string) $patientsCount) ?></strong>
                </article>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="container panel-card admin-list-card">
            <div class="panel-card__header">
                <div>
                    <p class="section-eyebrow">Заявки на приём</p>
                    <h2>Все записи пациентов</h2>
                </div>
                <p class="admin-note">Статус меняется сразу после сохранения.</p>
            </div>

            <?php if ($appointments === []): ?>
                <div class="empty-state">
                    <h3>Записей пока нет</h3>
                    <p>Новые заявки пациентов появятся здесь после оформления визита.</p>
                </div>
            <?php else: ?>
                <div class="admin-appointments-list">
                    <?php foreach ($appointments as $appointment): $view = present_appointment($appointment); ?>
                        <article class="appointment-card admin-appointment-card" data-id="<?= e((string) $view['id']) ?>">
                            <div class="appointment-card__top">
                                <div>
                                    <p class="appointment-card__eyebrow">Визит #<?= e((string) $view['id']) ?></p>
                                    <h3><?= e($view['service']) ?></h3>
                                </div>
                                <span class="status-badge status-badge--<?= e($view['status_class']) ?>" data-status-badge>
                                    <?= e($view['status']) ?>
                                </span>
                            </div>
                            <div class="appointment-card__meta">
                                <span><?= e($view['patient_name']) ?></span>
                                <span><?= e($view['patient_email']) ?></span>
                                <span><?= e($view['doctor']) ?></span>
                                <span><?= e($view['date_label']) ?></span>
                                <span><?= e($view['time_label']) ?></span>
                            </div>
                            <p class="appointment-card__comment"><?= e($view['comment'] !== '' ? $view['comment'] : 'Комментарий не указан.') ?></p>
                            <form class="admin-status-form" data-status-form>
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= e((string) $view['id']) ?>">
                                <label class="field">
                                    <span>Статус записи</span>
                                    <select name="status" required>
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?= e($status) ?>" <?= $status === $view['status'] ? 'selected' : '' ?>>
                                                <?= e($status) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <button type="submit" class="button button--primary button--small">Сохранить статус</button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script id="admin-appointments-data" type="application/json"><?= json_for_html($presentedAppointments) ?></script>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
