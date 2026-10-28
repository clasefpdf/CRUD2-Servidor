<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

final class WorkShiftInUseException extends WorkShiftException
{
    public static function withId(int $id, ?Throwable $previous = null): self
    {
        return new self('No se puede eliminar el turno ' . $id . ': hay datos que dependen de él.', 0, $previous);
    }
}
