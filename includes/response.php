<?php
declare(strict_types=1);

function get_request_data(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (str_contains($contentType, 'application/json')) {
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw ?: '[]', true);

        return is_array($decoded) ? $decoded : [];
    }

    return $_POST;
}

function require_http_method(string $method): void
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== strtoupper($method)) {
        json_error('Метод запроса не поддерживается.', 405);
    }
}

function json_response(array $payload, int $statusCode = 200): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode(
        $payload,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}

function json_success(string $message, array $data = [], int $statusCode = 200): never
{
    json_response(
        [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ],
        $statusCode
    );
}

function json_error(string $message, int $statusCode = 400, array $errors = []): never
{
    json_response(
        [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ],
        $statusCode
    );
}
