<?php
declare(strict_types=1);

function ensure_storage_file(string $path): void
{
    $directory = dirname($path);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    if (!file_exists($path) || trim((string) file_get_contents($path)) === '') {
        file_put_contents($path, "[]\n", LOCK_EX);
    }
}

function read_json_storage(string $path): array
{
    ensure_storage_file($path);

    $content = file_get_contents($path);
    if ($content === false || trim($content) === '') {
        return [];
    }

    $decoded = json_decode($content, true);

    return is_array($decoded) ? $decoded : [];
}

function write_json_storage(string $path, array $records): bool
{
    ensure_storage_file($path);

    $handle = fopen($path, 'c+');
    if ($handle === false) {
        return false;
    }

    $payload = json_encode(
        array_values($records),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    if ($payload === false) {
        fclose($handle);
        return false;
    }

    $result = false;

    if (flock($handle, LOCK_EX)) {
        ftruncate($handle, 0);
        rewind($handle);
        $written = fwrite($handle, $payload . PHP_EOL);
        fflush($handle);
        flock($handle, LOCK_UN);
        $result = $written !== false;
    }

    fclose($handle);

    return $result;
}

function next_storage_id(array $records): int
{
    $ids = array_map(
        static fn(array $record): int => (int) ($record['id'] ?? 0),
        $records
    );

    return $ids === [] ? 1 : max($ids) + 1;
}
