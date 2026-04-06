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
                <div class="hero-trustline">
                    <span>Премиальная семейная стоматология</span>
                    <span>3 кабинета экспертного приёма</span>
                    <span>Ежедневно с 08:00 до 21:00</span>
                </div>
                <p class="section-eyebrow">SmileCare Dental Studio</p>
                <h1>Стоматология, в которую возвращаются за спокойствием и результатом.</h1>
                <p class="hero-text">
                    Переосмысляем визит к стоматологу: прозрачные планы лечения, деликатная коммуникация,
                    цифровая запись и медицинский сервис без стресса.
                </p>
                <div class="hero-actions">
                    <a href="<?= is_authenticated() ? 'appointments.php' : 'register.php' ?>" class="button button--primary button--large">Записаться онлайн</a>
                    <a href="#services" class="button button--ghost button--large">Смотреть услуги</a>
                </div>
                <div class="hero-stats">
                    <div class="info-chip">
                        <strong>12 лет</strong>
                        <span>практики и цифровой диагностики</span>
                    </div>
                    <div class="info-chip">
                        <strong>4.9/5</strong>
                        <span>средняя оценка пациентов</span>
                    </div>
                    <div class="info-chip">
                        <strong>1 визит</strong>
                        <span>на первичную консультацию и план лечения</span>
                    </div>
                </div>
                <div class="hero-note">
                    <strong>Первичная консультация</strong>
                    <span>Осмотр, фотопротокол, рекомендации и персональный маршрут лечения в одном приёме.</span>
                </div>
            </div>

            <div class="hero-card">
                <div class="hero-card__badge">
                    <span>SmileCare Selected</span>
                    <strong>Комфортный приём без спешки</strong>
                </div>
                <div class="hero-card__grid">
                    <div class="hero-card__panel hero-card__panel--schedule">
                        <p>Ближайшее окно</p>
                        <strong>Сегодня, 18:30</strong>
                        <span>Терапия / Анна Воронцова</span>
                    </div>
                    <div class="hero-card__panel hero-card__panel--accent">
                        <p>Акцент месяца</p>
                        <strong>Комплексная диагностика</strong>
                        <span>Снимки, консультация и понятный план визитов</span>
                    </div>
                </div>
                <div class="hero-card__showcase">
                    <div class="hero-showcase__card">
                        <p class="hero-showcase__eyebrow">Экспертные направления</p>
                        <ul class="hero-showcase__list">
                            <li><span>3D-диагностика</span><strong>без ожидания</strong></li>
                            <li><span>Лечение под микроскопом</span><strong>точно и бережно</strong></li>
                            <li><span>Эстетика улыбки</span><strong>натуральный результат</strong></li>
                        </ul>
                    </div>
                    <div class="hero-showcase__card hero-showcase__card--dark">
                        <p class="hero-showcase__eyebrow">Формат приёма</p>
                        <strong>45 минут внимания врача</strong>
                        <span>Мы объясняем план лечения, показываем этапы и фиксируем рекомендации после визита.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-block section-block--compact">
        <div class="container trust-ribbon">
            <div class="trust-ribbon__item">
                <strong>Точная диагностика</strong>
                <span>КТ, цифровой фотопротокол и планирование лечения до старта процедуры</span>
            </div>
            <div class="trust-ribbon__item">
                <strong>Команда узких специалистов</strong>
                <span>Терапия, хирургия, гигиена и эстетика в одной связанной системе</span>
            </div>
            <div class="trust-ribbon__item">
                <strong>Сервис без перегруза</strong>
                <span>Понятная навигация, мягкая коммуникация и прозрачные этапы для пациента</span>
            </div>
        </div>
    </section>

    <section class="section-block section-block--muted">
        <div class="container feature-grid">
            <article class="feature-card">
                <p class="section-eyebrow">О клинике</p>
                <h2>Сильная медицина без тяжёлой атмосферы.</h2>
                <p>
                    SmileCare сочетает терапию, эстетическую стоматологию и профилактику в одном маршруте:
                    от первичного осмотра до регулярного сопровождения через кабинет пациента.
                </p>
            </article>
            <article class="feature-card">
                <h3>Почему нас выбирают</h3>
                <ul class="feature-list">
                    <li>Прозрачный план лечения и понятные цены.</li>
                    <li>Собственная команда врачей узких специализаций.</li>
                    <li>Онлайн-запись и управление визитами без звонков.</li>
                    <li>Аккуратная, светлая и полностью адаптивная цифровая среда.</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="section-block">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Маршрут пациента</p>
                <h2>Путь от первого обращения до уверенной улыбки</h2>
            </div>
            <p>Мы строим процесс так, чтобы пациент понимал каждый следующий шаг и не терялся в сложных медицинских решениях.</p>
        </div>
        <div class="container care-route">
            <article class="care-route__step">
                <span class="care-route__index">01</span>
                <h3>Диагностика и знакомство</h3>
                <p>Первичный приём начинается с осмотра, фотопротокола и обсуждения жалоб без спешки.</p>
            </article>
            <article class="care-route__step">
                <span class="care-route__index">02</span>
                <h3>Персональный план лечения</h3>
                <p>Врач объясняет последовательность этапов, приоритеты и ориентиры по стоимости до начала лечения.</p>
            </article>
            <article class="care-route__step">
                <span class="care-route__index">03</span>
                <h3>Бережное лечение</h3>
                <p>Работаем под увеличением, контролируем комфорт и сохраняем прогнозируемый клинический результат.</p>
            </article>
            <article class="care-route__step">
                <span class="care-route__index">04</span>
                <h3>Поддержка после визита</h3>
                <p>Фиксируем рекомендации, контрольные визиты и дальнейшие шаги, чтобы лечение было последовательным.</p>
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
                <span class="service-card__icon">01</span>
                <h3>Лечение кариеса</h3>
                <p>Терапия под увеличением, фотопротокол, современная анестезия и прогнозируемый результат.</p>
                <div class="service-card__tags">
                    <span>Терапия</span>
                    <span>Микроскоп</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">02</span>
                <h3>Профессиональная чистка</h3>
                <p>Гигиена Air Flow + ультразвук, индивидуальные рекомендации по домашнему уходу.</p>
                <div class="service-card__tags">
                    <span>Профилактика</span>
                    <span>Air Flow</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">03</span>
                <h3>Имплантация</h3>
                <p>Планирование по КТ и сопровождение хирурга на каждом этапе восстановления зубного ряда.</p>
                <div class="service-card__tags">
                    <span>Хирургия</span>
                    <span>КТ</span>
                </div>
            </article>
            <article class="service-card">
                <span class="service-card__icon">04</span>
                <h3>Отбеливание</h3>
                <p>Эстетический протокол с контролем чувствительности и персональным подбором курса.</p>
                <div class="service-card__tags">
                    <span>Эстетика</span>
                    <span>ZOOM</span>
                </div>
            </article>
        </div>
    </section>

    <section class="section-block section-block--gradient" id="doctors">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Команда</p>
                <h2>Врачи, которым доверяют сложные кейсы</h2>
            </div>
            <p>Каждый специалист работает в своей зоне экспертизы и подключается к единому плану лечения пациента.</p>
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

    <section class="section-block section-block--muted">
        <div class="container section-heading">
            <div>
                <p class="section-eyebrow">Отзывы</p>
                <h2>Что пациенты отмечают чаще всего</h2>
            </div>
            <p>Несколько коротких историй о том, за что пациенты ценят SmileCare.</p>
        </div>
        <div class="container review-slider" data-review-slider>
            <article class="review-card is-active" data-review-slide>
                <p>“Очень спокойная атмосфера и редкое ощущение, что тебе всё объясняют человеческим языком. Онлайн-запись реально удобная.”</p>
                <span>Екатерина, терапия</span>
            </article>
            <article class="review-card" data-review-slide>
                <p>“Записалась без звонков, сразу увидела детали визита и быстро выбрала удобное время. Всё очень понятно и спокойно.”</p>
                <span>Мария, гигиена</span>
            </article>
            <article class="review-card" data-review-slide>
                <p>“Понравилось, что цены прозрачные, а личный кабинет позволяет быстро перенести запись и следить за датами.”</p>
                <span>Алексей, консультация</span>
            </article>
            <div class="review-slider__controls">
                <button type="button" class="button button--ghost button--small" data-review-nav="prev">Назад</button>
                <button type="button" class="button button--ghost button--small" data-review-nav="next">Вперёд</button>
            </div>
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
                <p class="section-eyebrow">Личный кабинет пациента</p>
                <h2>Управляйте визитами онлайн: выбирайте удобное время и следите за актуальными записями.</h2>
            </div>
            <a href="<?= is_authenticated() ? 'appointments.php' : 'login.php' ?>" class="button button--dark button--large">Открыть кабинет</a>
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
            <article class="contact-card contact-card--map">
                <div class="map-placeholder">
                    <span>SmileCare</span>
                    <strong>Медицинский центр с комфортной навигацией и удобной записью</strong>
                    <p>Мы находимся в центре города, рядом с удобной парковкой и остановками общественного транспорта.</p>
                </div>
            </article>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
