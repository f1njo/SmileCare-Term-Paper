<?php
declare(strict_types=1);

$pageScripts = $pageScripts ?? [];
$flash = pull_flash();
?>
    <footer class="site-footer">
        <div class="container footer-shell">
            <div>
                <a href="index.php" class="brand brand--footer">
                    <span class="brand__mark">SC</span>
                    <span class="brand__text">
                        <strong>SmileCare</strong>
                        <small>цифровая стоматология для всей семьи</small>
                    </span>
                </a>
                <p class="footer-note">Современная стоматология с удобной онлайн-записью и внимательным сопровождением пациентов.</p>
            </div>
            <div class="footer-links">
                <a href="index.php#services">Услуги</a>
                <a href="index.php#prices">Цены</a>
                <a href="index.php#contacts">Контакты</a>
                <a href="appointments.php">Онлайн-запись</a>
            </div>
            <div class="footer-contacts">
                <p>г. Красноярск, ул. Мира, 18</p>
                <p>+7 (999) 123-45-67</p>
                <p>Ежедневно 08:00-21:00</p>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container footer-bottom__inner">
                <span>© 2026 SmileCare. Все права защищены.</span>
                <span>Терапия, эстетика, профилактика и цифровой сервис</span>
            </div>
        </div>
    </footer>
</div>

<div
    class="toast-stack"
    id="toast-stack"
    data-flash-message="<?= e($flash['message'] ?? '') ?>"
    data-flash-type="<?= e($flash['type'] ?? 'info') ?>"
></div>

<script>
    window.SmileCareConfig = {
        csrfToken: <?= json_encode(csrf_token(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    };
</script>
<script src="<?= e(asset('js/main.js')) ?>" defer></script>
<?php foreach ($pageScripts as $script): ?>
    <script src="<?= e(asset('js/' . ltrim($script, '/'))) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
