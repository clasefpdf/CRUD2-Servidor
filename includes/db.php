<?php

declare(strict_types=1);

function getDB(): PDO
{
    static $db = null;
    if ($db === null) {
        $directory = dirname(DB_PATH);
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('No es pot crear el directori de dades.');
        }
        $db = new PDO('sqlite:' . DB_PATH, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $db->exec('PRAGMA foreign_keys = ON');
    }
    return $db;
}

function initDB(): void
{
    $db = getDB();
    $db->exec(file_get_contents(__DIR__ . '/../sql/schema.sql'));
    if ((int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
        $db->beginTransaction();
        try {
            $db->exec(file_get_contents(__DIR__ . '/../sql/seed.sql'));
            $password = password_hash('1234', PASSWORD_DEFAULT);
            $stmt = $db->prepare('UPDATE users SET password = ?');
            $stmt->execute([$password]);
            $db->commit();
        } catch (Throwable $exception) {
            $db->rollBack();
            throw $exception;
        }
    }
}
