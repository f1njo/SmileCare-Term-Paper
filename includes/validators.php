<?php
declare(strict_types=1);

function normalize_email(string $email): string
{
    return mb_strtolower(trim($email));
}

function validate_registration_input(array $input): array
{
    $name = trim((string) ($input['name'] ?? ''));
    $email = normalize_email((string) ($input['email'] ?? ''));
    $password = (string) ($input['password'] ?? '');
    $passwordConfirm = (string) ($input['password_confirm'] ?? '');
    $errors = [];

    if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 80) {
        $errors['name'] = 'Укажите имя от 2 до 80 символов.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email.';
    }

    if (mb_strlen($password) < 8) {
        $errors['password'] = 'Пароль должен содержать минимум 8 символов.';
    }

    if ($password !== $passwordConfirm) {
        $errors['password_confirm'] = 'Пароли не совпадают.';
    }

    return [
        'values' => [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ],
        'errors' => $errors,
    ];
}

function validate_login_input(array $input): array
{
    $email = normalize_email((string) ($input['email'] ?? ''));
    $password = (string) ($input['password'] ?? '');
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email.';
    }

    if ($password === '') {
        $errors['password'] = 'Введите пароль.';
    }

    return [
        'values' => [
            'email' => $email,
            'password' => $password,
        ],
        'errors' => $errors,
    ];
}

function validate_appointment_input(array $input): array
{
    $service = trim((string) ($input['service'] ?? ''));
    $doctor = trim((string) ($input['doctor'] ?? ''));
    $appointmentDate = trim((string) ($input['appointment_date'] ?? ''));
    $appointmentTime = trim((string) ($input['appointment_time'] ?? ''));
    $comment = trim((string) ($input['comment'] ?? ''));
    $errors = [];

    if (!in_array($service, clinic_services(), true)) {
        $errors['service'] = 'Выберите услугу из списка.';
    }

    if (!in_array($doctor, clinic_doctors(), true)) {
        $errors['doctor'] = 'Выберите врача из списка.';
    }

    $dateObject = DateTimeImmutable::createFromFormat('Y-m-d', $appointmentDate);
    if (!$dateObject || $dateObject->format('Y-m-d') !== $appointmentDate) {
        $errors['appointment_date'] = 'Укажите корректную дату.';
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $appointmentTime)) {
        $errors['appointment_time'] = 'Укажите время в формате ЧЧ:ММ.';
    }

    if ($comment !== '' && mb_strlen($comment) > 500) {
        $errors['comment'] = 'Комментарий не должен превышать 500 символов.';
    }

    if (!isset($errors['appointment_date']) && !isset($errors['appointment_time'])) {
        $appointmentAt = strtotime($appointmentDate . ' ' . $appointmentTime);
        if ($appointmentAt === false || $appointmentAt < time()) {
            $errors['appointment_date'] = 'Запись можно создать только на будущую дату и время.';
        }
    }

    return [
        'values' => [
            'service' => $service,
            'doctor' => $doctor,
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'comment' => $comment,
            'status' => 'Ожидает подтверждения',
        ],
        'errors' => $errors,
    ];
}
