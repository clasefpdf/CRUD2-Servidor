<?php

declare(strict_types=1);

namespace App\Exception;

final class WorkShiftNotFoundException extends WorkShiftException
{
    public static function withId(int $id): self
    {
        return new self('Turno no encontrado (id ' . $id . ').');
    }
}
