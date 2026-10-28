<?php

declare(strict_types=1);

namespace App\Validation;

use App\WorkShift;

final class WorkShiftValidator
{
    /** @return list<string> */
    public function validate(array $input): array
    {
        $errors = [];

        $name = is_string($input['name'] ?? null) ? trim($input['name']) : '';
        if ($name === '' || mb_strlen($name) > WorkShift::NAME_MAX_LENGTH) {
            $errors[] = 'El nombre es obligatorio y no puede superar los ' . WorkShift::NAME_MAX_LENGTH . ' caracteres.';
        }

        $start = is_string($input['start_time'] ?? null) ? $input['start_time'] : '';
        $end = is_string($input['end_time'] ?? null) ? $input['end_time'] : '';
        $startOk = WorkShift::isValidTime($start);
        $endOk = WorkShift::isValidTime($end);
        if (!$startOk) {
            $errors[] = 'La hora de inicio no es válida (formato HH:MM).';
        }
        if (!$endOk) {
            $errors[] = 'La hora de fin no es válida (formato HH:MM).';
        }
        if ($startOk && $endOk && $start === $end) {
            $errors[] = 'La hora de inicio y la de fin no pueden coincidir.';
        }

        return $errors;
    }
}