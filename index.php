<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'SmileCare | Премиальная стоматология и онлайн-запись';
$pageDescription = 'Современный landing page стоматологической клиники SmileCare с ценами, врачами, акциями и цифровой записью на приём.';
$pageClass = 'landing-page';

include __DIR__ . '/includes/header.php';
?>
<main>
    <section class="hero-section">
        <div class="container hero-grid">
            <div class="hero-copy">
                <p class="section-eyebrow">SmileCare Dental Clinic</p>
                <h1>Современная стоматология для спокойного лечения.</h1>
                <p class="hero-text">
                    Лечение, профилактика и эстетика улыбки в светлой клинике с понятными ценами,
                    внимательными врачами и удобной онлайн-записью.
                </p>
                <div class="hero-actions">
                    <a href="<?= is_authenticated() ? 'appointments.php' : 'register.php' ?>" class="button button--primary button--large">Записаться онлайн</a>
                    <a href="#services" class="button button--ghost button--large">Смотреть услуги</a>
                </div>
                <div class="hero-stats">
                    <div class="info-chip">
                        <strong>12 лет</strong>
                        <span>клинической практики</span>
                    </div>
                    <div class="info-chip">
                        <strong>4.9/5</strong>
                        <span>оценка пациентов</span>
                    </div>
                    <div class="info-chip">
                        <strong>08:00-21:00</strong>
                        <span>ежедневный приём</span>
                    </div>
                </div>
            </div>

            <div class="hero-card" aria-label="Карточка записи в SmileCare">
                <div class="hero-visual">
                    <div class="hero-visual__image">
                        <span>SC</span>
                    </div>
                    <div class="hero-visual__content">
                        <p>Ближайшая запись</p>
                        <strong>Сегодня, 18:30</strong>
                        <span>Терапевт Анна Воронцова</span>
                    </div>
                </div>
                <div class="hero-card__panel">
                    <p>Первичная консультация</p>
                    <strong><?= e(format_currency(500)) ?></strong>
                    <span>Осмотр, рекомендации и план лечения за один визит.</span>
                </div>
                <ul class="hero-checklist">
                    <li>Безболезненная анестезия</li>
                    <li>Цифровая диагностика</li>
                    <li>Понятный план лечения</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="section-block section-block--muted" id="benefits">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Преимущества</p>
                <h2>Заботимся о лечении, сервисе и спокойствии пациента</h2>
            </div>
            <p>Все этапы понятны заранее: от диагностики до стоимости и следующего визита.</p>
        </div>
        <div class="container cards-grid cards-grid--benefits">
            <article class="feature-card">
                <span class="feature-card__icon">01</span>
                <h3>Точная диагностика</h3>
                <p>Осмотр, снимки и фотопротокол помогают составить понятный план лечения.</p>
            </article>
            <article class="feature-card">
                <span class="feature-card__icon">02</span>
                <h3>Команда специалистов</h3>
                <p>Терапевт, хирург, гигиенист и эстетический стоматолог работают в одной системе.</p>
            </article>
            <article class="feature-card">
                <span class="feature-card__icon">03</span>
                <h3>Онлайн-запись</h3>
                <p>Пациент может выбрать услугу, врача и удобное время без лишних звонков.</p>
            </article>
            <article class="feature-card">
                <span class="feature-card__icon">04</span>
                <h3>Прозрачные цены</h3>
                <p>Стоимость ключевых услуг указана заранее и не теряется в мелких деталях.</p>
            </article>
        </div>
    </section>

    <section class="section-block" id="services">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Услуги</p>
                <h2>Основные направления SmileCare</h2>
            </div>
            <p>Основные направления лечения, профилактики и эстетической стоматологии в одной клинике.</p>
        </div>
        <div class="container cards-grid cards-grid--services">
            <article class="service-card">
                <span class="service-card__icon">+</span>
                <h3>Лечение кариеса</h3>
                <p>Терапия под увеличением, фотопротокол, современная анестезия и прогнозируемый результат.</p>
                <div class="service-card__tags">
                    <span>Терапия</span>
                    <span>Микроскоп</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">+</span>
                <h3>Профессиональная чистка</h3>
                <p>Гигиена Air Flow + ультразвук, индивидуальные рекомендации по домашнему уходу.</p>
                <div class="service-card__tags">
                    <span>Профилактика</span>
                    <span>Air Flow</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">+</span>
                <h3>Имплантация</h3>
                <p>Планирование по КТ и сопровождение хирурга на каждом этапе восстановления зубного ряда.</p>
                <div class="service-card__tags">
                    <span>Хирургия</span>
                    <span>КТ</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">+</span>
                <h3>Отбеливание</h3>
                <p>Эстетический протокол с контролем чувствительности и персональным подбором курса.</p>
                <div class="service-card__tags">
                    <span>Эстетика</span>
                    <span>ZOOM</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">+</span>
                <h3>Эстетическая реставрация</h3>
                <p>Восстановление формы и цвета зуба с естественным результатом.</p>
                <div class="service-card__tags">
                    <span>Эстетика</span>
                    <span>Реставрация</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">+</span>
                <h3>Детская стоматология</h3>
                <p>Мягкий приём, профилактика и лечение зубов у детей без лишнего стресса.</p>
                <div class="service-card__tags">
                    <span>Дети</span>
                    <span>Профилактика</span>
                </div>
            </article>
        </div>
    </section>

    <section class="section-block section-block--muted" id="doctors">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Команда</p>
                <h2>Врачи SmileCare</h2>
            </div>
            <p>Каждый специалист отвечает за своё направление и объясняет лечение простым языком.</p>
        </div>
        <div class="container cards-grid cards-grid--doctors">
            <article class="doctor-card">
                <div class="doctor-card__avatar">АВ</div>
                <p class="doctor-card__role">Эндодонтия и реставрация</p>
                <h3>Анна Воронцова</h3>
                <p>Терапевт-эндодонтист. Микроскоп, художественная реставрация, сложные каналы.</p>
            </article>
            <article class="doctor-card">
                <div class="doctor-card__avatar">ИМ</div>
                <p class="doctor-card__role">Имплантация и хирургия</p>
                <h3>Илья Мельников</h3>
                <p>Хирург-имплантолог. Атравматичные протоколы, навигационная имплантация.</p>
            </article>
            <article class="doctor-card">
                <div class="doctor-card__avatar">МЖ</div>
                <p class="doctor-card__role">Профилактика и гигиена</p>
                <h3>Марина Жданова</h3>
                <p>Гигиенист и специалист по профилактике. Сопровождает пациентов по домашнему уходу.</p>
            </article>
            <article class="doctor-card">
                <div class="doctor-card__avatar">ОК</div>
                <p class="doctor-card__role">Детская стоматология</p>
                <h3>Ольга Ковалева</h3>
                <p>Детский стоматолог. Помогает детям привыкнуть к лечению спокойно и без страха.</p>
            </article>
        </div>
    </section>

    <section class="section-block" id="prices">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Цены</p>
                <h2>Понятные тарифы без скрытых услуг</h2>
            </div>
            <p>Прозрачные цены на ключевые услуги без скрытых условий и лишних деталей.</p>
        </div>
        <div class="container pricing-grid">
            <article class="pricing-card">
                <h3>Первичный осмотр</h3>
                <strong><?= e(format_currency(500)) ?></strong>
                <p>Осмотр, консультация врача, рекомендации и цифровой маршрут лечения.</p>
                <ul class="pricing-card__features">
                    <li>Знакомство с врачом</li>
                    <li>Оценка состояния полости рта</li>
                    <li>Рекомендации по следующему визиту</li>
                </ul>
            </article>
            <article class="pricing-card pricing-card--featured">
                <span class="pricing-card__badge">Популярно</span>
                <h3>Лечение кариеса</h3>
                <strong><?= e(format_currency(3000)) ?></strong>
                <p>Восстановление зуба современными материалами и фотопротокол до/после.</p>
                <ul class="pricing-card__features">
                    <li>Современная анестезия</li>
                    <li>Щадящий протокол лечения</li>
                    <li>Эстетичное восстановление формы</li>
                </ul>
            </article>
            <article class="pricing-card">
                <h3>Отбеливание</h3>
                <strong><?= e(format_currency(7000)) ?></strong>
                <p>Курсовая эстетическая процедура с бережным контролем чувствительности.</p>
                <ul class="pricing-card__features">
                    <li>Подбор подходящего протокола</li>
                    <li>Контроль чувствительности</li>
                    <li>Рекомендации по сохранению результата</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-block">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Отзывы</p>
                <h2>Пациенты отмечают спокойствие и понятный сервис</h2>
            </div>
            <p>Короткие отзывы о лечении, гигиене и онлайн-записи.</p>
        </div>
        <div class="container cards-grid cards-grid--reviews">
            <article class="review-card">
                <p>Очень спокойная атмосфера и редкое ощущение, что тебе всё объясняют человеческим языком. Онлайн-запись реально удобная.</p>
                <span>Екатерина, терапия</span>
            </article>
            <article class="review-card">
                <p>Записалась без звонков, сразу увидела детали визита и быстро выбрала удобное время. Всё очень понятно.</p>
                <span>Мария, гигиена</span>
            </article>
            <article class="review-card">
                <p>Понравилось, что цены прозрачные, а личный кабинет позволяет быстро перенести запись и следить за датами.</p>
                <span>Алексей, консультация</span>
            </article>
        </div>
    </section>

    <section class="section-block" id="faq">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">FAQ</p>
                <h2>Частые вопросы перед первым визитом</h2>
            </div>
            <p>Ответы на важные вопросы перед консультацией, лечением и первым посещением клиники.</p>
        </div>
        <div class="container faq-list">
            <article class="faq-item is-open">
                <button class="faq-item__trigger" type="button" data-faq-trigger aria-expanded="true">
                    Можно ли записаться без звонка?
                </button>
                <div class="faq-item__content">
                    <p>Да. После входа в личный кабинет вы можете выбрать услугу, врача, дату и время визита онлайн.</p>
                </div>
            </article>
            <article class="faq-item">
                <button class="faq-item__trigger" type="button" data-faq-trigger aria-expanded="false">
                    Как быстро подтверждается запись?
                </button>
                <div class="faq-item__content">
                    <p>После отправки заявки администратор уточняет детали и подтверждает время посещения.</p>
                </div>
            </article>
            <article class="faq-item">
                <button class="faq-item__trigger" type="button" data-faq-trigger aria-expanded="false">
                    Данные пациентов защищены?
                </button>
                <div class="faq-item__content">
                    <p>Да. Доступ к личному кабинету защищён, а информация о визитах доступна только владельцу аккаунта.</p>
                </div>
            </article>
        </div>
    </section>

    <section class="section-block section-block--accent">
        <div class="container cta-banner">
            <div>
                <p class="section-eyebrow">Онлайн-запись</p>
                <h2>Выберите удобное время приёма и получите подтверждение администратора.</h2>
            </div>
            <a href="<?= is_authenticated() ? 'appointments.php' : 'register.php' ?>" class="button button--dark button--large">Записаться на приём</a>
        </div>
    </section>

    <section class="section-block" id="contacts">
        <div class="container contacts-grid">
            <article class="contact-card">
                <p class="section-eyebrow">Контакты</p>
                <h2>SmileCare, Красноярск</h2>
                <ul class="contact-list">
                    <li>+7 (999) 123-45-67</li>
                    <li>г. Красноярск, ул. Мира, 18</li>
                    <li>Пн-Вс: 08:00-21:00</li>
                    <li>hello@smilecare.local</li>
                </ul>
            </article>
            <article class="contact-card contact-card--form">
                <p class="section-eyebrow">Быстрая заявка</p>
                <h2>Записаться на консультацию</h2>
                <form class="landing-form" action="<?= is_authenticated() ? 'appointments.php' : 'register.php' ?>" method="get">
                    <label class="field">
                        <span>Имя</span>
                        <input type="text" name="name" placeholder="Анна Иванова">
                    </label>
                    <label class="field">
                        <span>Телефон или email</span>
                        <input type="text" name="contact" placeholder="+7 999 123-45-67">
                    </label>
                    <label class="field">
                        <span>Услуга</span>
                        <select name="service">
                            <option value="">Выберите услугу</option>
                            <?php foreach (clinic_services() as $service): ?>
                                <option value="<?= e($service) ?>"><?= e($service) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <button type="submit" class="button button--primary button--large button--block">Отправить заявку</button>
                </form>
            </article>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
