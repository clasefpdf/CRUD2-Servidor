<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/log.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/csrf.php';
require_once  __DIR__. '/auth.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true]);
}

try {
    initDB();
} catch (Throwable $exception) {
    applicationLogger()->error('No s’ha pogut inicialitzar la base de dades.', ['exception' => $exception]);
    http_response_code(500);
    exit('Error intern. Torna-ho a provar més tard.');
}
