# SmileCare

Веб-приложение стоматологической клиники, разработанное в рамках курсовой работы.

## Используемые технологии

### Backend
- PHP
- MySQL
- PDO

### Frontend
- HTML
- CSS
- JavaScript
- AJAX (Fetch API)

## Возможности

### Пациент
- Регистрация
- Авторизация
- Запись на приём
- Просмотр своих записей
- Редактирование записей
- Удаление записей

### Врач
- Просмотр назначенных записей
- Просмотр информации о пациенте
- Изменение статуса приёма

### Администратор
- Просмотр всех данных системы
- Управление пользователями
- Управление врачами
- Управление услугами

## Структура проекта
SmileCare-Term-Paper-main/
│── api/
│── assets/
│── includes/
│── database/
│──── smilecare_db.sql
│── index.php
│── login.php
│── register.php
│── dashboard.php
│── appointments.php
│── logout.php
└── README.md

## Настройка базы данных

1. Открыть MySQL Workbench
2. Перейти:
Server → Data Import

3. Выбрать:
Import from Self-Contained File

4. Указать файл:
database/smilecare_db.sql

5. Выбрать базу данных:
smilecare_db

6. Нажать:
Start Import

## Настройка подключения

Файл подключения:
includes/db.php

Проверить настройки:
$host = 'localhost';
$dbname = 'smilecare_db';
$user = 'root';
$password = '';

## Тестовые аккаунты

Пароль для всех аккаунтов:
TEST

### Администратор
admin@smilecare.ru

### Врачи
vorontsova@smilecare.ru
melnikov@smilecare.ru
zhdanova@smilecare.ru
kovaleva@smilecare.ru

### Пациенты
ivan@gmail.com
maria@gmail.com

## Безопасность

- Хеширование паролей (`password_hash`)
- Проверка пароля (`password_verify`)
- PDO prepared statements
- Сессии PHP
- Разграничение доступа по ролям