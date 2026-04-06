<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

logout_user();
set_flash('success', 'Вы вышли из личного кабинета.');

redirect_to('index.php');
