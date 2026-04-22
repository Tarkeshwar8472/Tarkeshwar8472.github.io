<?php
declare(strict_types=1);

const SANDIP_DB_HOST = '127.0.0.1';
const SANDIP_DB_PORT = '3306';
const SANDIP_DB_NAME = 'sandip_foundation';
const SANDIP_DB_USER = 'root';
const SANDIP_DB_PASS = '';
const SANDIP_JSON_STORE = __DIR__ . '/storage/admissions.json';

function sandip_get_db_connection(): ?PDO
{
    try {
        $dsn = 'mysql:host=' . SANDIP_DB_HOST . ';port=' . SANDIP_DB_PORT . ';dbname=' . SANDIP_DB_NAME . ';charset=utf8mb4';
        return new PDO($dsn, SANDIP_DB_USER, SANDIP_DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $exception) {
        return null;
    }
}

function sandip_read_json_records(): array
{
    if (!file_exists(SANDIP_JSON_STORE)) {
        return [];
    }

    $content = file_get_contents(SANDIP_JSON_STORE);
    if ($content === false || trim($content) === '') {
        return [];
    }

    $decoded = json_decode($content, true);
    return is_array($decoded) ? $decoded : [];
}

function sandip_save_json_record(array $record): void
{
    $directory = dirname(SANDIP_JSON_STORE);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $records = sandip_read_json_records();
    array_unshift($records, $record);
    file_put_contents(SANDIP_JSON_STORE, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
