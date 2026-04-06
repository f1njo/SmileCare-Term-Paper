<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function asset(string $path): string
{
    return 'assets/' . ltrim($path, '/');
}

function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function page_is(string $page): bool
{
    return basename($_SERVER['PHP_SELF'] ?? '') === $page;
}

function redirect_to(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function pull_flash(): ?array
{
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function clinic_services(): array
{
    return [
        'Профилактический осмотр',
        'Лечение кариеса',
        'Профессиональная гигиена',
        'Эстетическая реставрация',
        'Отбеливание ZOOM',
        'Имплантация',
        'Детская стоматология',
    ];
}

function clinic_doctors(): array
{
    return [
        'Анна Воронцова',
        'Илья Мельников',
        'Марина Жданова',
        'Ольга Ковалева',
    ];
}

function appointment_statuses(): array
{
    return [
        'Ожидает подтверждения',
        'Подтверждена',
        'Завершена',
        'Отменена',
    ];
}

function format_currency(int $amount): string
{
    return number_format($amount, 0, ',', ' ') . ' ₽';
}

function format_date_ru(string $date): string
{
    $dateObject = DateTimeImmutable::createFromFormat('Y-m-d', $date);

    return $dateObject ? $dateObject->format('d.m.Y') : $date;
}

function format_time_ru(string $time): string
{
    return preg_match('/^\d{2}:\d{2}$/', $time) ? $time : $time;
}

function format_appointment_datetime(array $appointment): string
{
    $date = format_date_ru((string) ($appointment['appointment_date'] ?? ''));
    $time = format_time_ru((string) ($appointment['appointment_time'] ?? ''));

    return trim($date . ' в ' . $time);
}

function appointment_timestamp(array $appointment): int
{
    $date = (string) ($appointment['appointment_date'] ?? '');
    $time = (string) ($appointment['appointment_time'] ?? '00:00');
    $timestamp = strtotime($date . ' ' . $time);

    return $timestamp ?: 0;
}

function sort_appointments(array $appointments, string $direction = 'desc'): array
{
    usort(
        $appointments,
        static function (array $left, array $right) use ($direction): int {
            $leftTime = appointment_timestamp($left);
            $rightTime = appointment_timestamp($right);

            return $direction === 'asc' ? $leftTime <=> $rightTime : $rightTime <=> $leftTime;
        }
    );

    return $appointments;
}

function next_upcoming_appointment(array $appointments): ?array
{
    $now = time();
    $upcoming = array_filter(
        $appointments,
        static fn(array $appointment): bool => appointment_timestamp($appointment) >= $now
    );

    if ($upcoming === []) {
        return null;
    }

    $upcoming = sort_appointments(array_values($upcoming), 'asc');

    return $upcoming[0] ?? null;
}

function json_for_html(mixed $data): string
{
    $encoded = json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
        | JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
    );

    return $encoded === false ? '[]' : $encoded;
}
