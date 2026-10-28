<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

final class DuplicateWorkShiftException extends WorkShiftException
{
    public static function forName(string $name, ?Throwable $previous = null): self
    {
        return new self('Ya existe un turno llamado «' . $name . '».', 0, $previous);
    }
}
