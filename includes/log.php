<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

function applicationLogger(): Logger
{
    static $logger = null;

    if ($logger === null) {
        $logger = new Logger('sprintboard');
        $logger->pushHandler(new StreamHandler('php://stderr'));
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../var/error.log'));
    }

    return $logger;
}
