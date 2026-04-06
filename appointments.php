<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

require_auth_page();

$user = current_user();
$appointments = appointments_for_user((int) $user['id']);
$presentedAppointments = array_map('present_appointment', $appointments);
$pageTitle = 'Записи на приём | SmileCare';
$pageDescription = 'Страница управления записями на приём в клинику SmileCare.';
$pageClass = 'appointments-page';
$pageScripts = ['appointments.js'];

include __DIR__ . '/includes/header.php';
?>
<main class="appointments-main" data-appointments-app data-delete-url="api/appointments/delete.php">
    <section class="appointments-hero">
        <div class="container appointments-hero__grid">
            <div>
                <p class="section-eyebrow">Функциональная страница</p>
                <h1>Онлайн-запись на приём и управление визитами.</h1>
                <p class="hero-text">
                    Выберите услугу, врача, дату и время визита, а затем управляйте своими записями в одном разделе.
                </p>
            </div>
            <div class="appointments-hero__stats">
                <article class="dashboard-stat">
                    <span>Записей в системе</span>
                    <strong data-stat-total><?= e((string) count($appointments)) ?></strong>
                </article>
                <article class="dashboard-stat">
                    <span>Ожидают подтверждения</span>
                    <strong data-stat-pending><?= e((string) count(array_filter($appointments, static fn(array $item): bool => ($item['status'] ?? '') === 'Ожидает подтверждения'))) ?></strong>
                </article>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="container appointments-layout">
            <aside class="panel-card appointment-form-card">
                <div class="panel-card__header">
                    <div>
                        <p class="section-eyebrow">Форма записи</p>
                        <h2 data-form-title>Создать новую запись</h2>
                    </div>
                </div>

                <form
                    id="appointment-form"
                    class="appointment-form"
                    method="post"
                    action="api/appointments/create.php"
                    data-create-url="api/appointments/create.php"
                    data-update-url="api/appointments/update.php"
                    novalidate
                >
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="id" value="">

                    <label class="field">
                        <span>Услуга</span>
                        <select name="service" required>
                            <option value="">Выберите услугу</option>
                            <?php foreach (clinic_services() as $service): ?>
                                <option value="<?= e($service) ?>"><?= e($service) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="field-error" data-error-for="service"></small>
                    </label>

                    <label class="field">
                        <span>Врач</span>
                        <select name="doctor" required>
                            <option value="">Выберите врача</option>
                            <?php foreach (clinic_doctors() as $doctor): ?>
                                <option value="<?= e($doctor) ?>"><?= e($doctor) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="field-error" data-error-for="doctor"></small>
                    </label>

                    <div class="form-grid">
                        <label class="field">
                            <span>Дата</span>
                            <input type="date" name="appointment_date" required>
                            <small class="field-error" data-error-for="appointment_date"></small>
                        </label>

                        <label class="field">
                            <span>Время</span>
                            <input type="time" name="appointment_time" required>
                            <small class="field-error" data-error-for="appointment_time"></small>
                        </label>
                    </div>

                    <label class="field">
                        <span>Комментарий</span>
                        <textarea name="comment" rows="5" placeholder="Например: чувствительность зуба, пожелания по времени, уточнения по записи."></textarea>
                        <small class="field-error" data-error-for="comment"></small>
                    </label>

                    <div class="form-message" data-form-message></div>
                    <div class="appointment-form__actions">
                        <button type="submit" class="button button--primary button--large">
                            <span data-submit-label>Создать запись</span>
                        </button>
                        <button type="button" class="button button--ghost" data-cancel-edit hidden>Отменить редактирование</button>
                    </div>
                </form>
            </aside>

            <section class="panel-card appointment-list-card">
                <div class="panel-card__header">
                    <div>
                        <p class="section-eyebrow">Список записей</p>
                        <h2>Ваши визиты в SmileCare</h2>
                    </div>
                </div>

                <div class="empty-state <?= $appointments === [] ? '' : 'is-hidden' ?>" id="appointments-empty">
                    <h3>Записей пока нет</h3>
                    <p>Создайте первую запись через форму слева. После отправки она сразу появится в вашем списке визитов.</p>
                </div>

                <div class="appointments-list <?= $appointments === [] ? 'is-hidden' : '' ?>" id="appointments-list">
                    <?php foreach ($appointments as $appointment): ?>
                        <?= render_appointment_card($appointment) ?>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </section>

    <script id="appointments-data" type="application/json"><?= json_for_html($presentedAppointments) ?></script>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
