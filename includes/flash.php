<?php

declare(strict_types=1);

use App\Util\Flash;

function setFlash(string $type, string $message): void
{
    Flash::set($type, $message);
}