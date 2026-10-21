<?php

declare(strict_types=1);

namespace App\Util;

final class Flash
{
    public static function set(string $type, string $message): void
    {
        $_SESSION['flashes'][] = ['type' => $type, 'message' => $message];
    }

    public static function get(): array
    {
        $flashes = $_SESSION['flashes'] ?? [];
        unset($_SESSION['flashes']);

        return $flashes;
    }
}