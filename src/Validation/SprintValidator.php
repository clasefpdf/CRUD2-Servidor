<?php

declare(strict_types=1);

namespace App\Validation;

final class SprintValidator
{
    /** @return list<string> */
    public function validate(array $input): array
    {
        $errors = [];
        $name = trim((string) ($input['name'] ?? ''));
        if ($name === '' || mb_strlen($name) > 100) {
            $errors[] = 'El nom del sprint és obligatori i no pot superar els 100 caràcters.';
        }
        $description = trim((string) ($input['description'] ?? ''));
        if ($description === '' || mb_strlen($description) > 2000) {
            $errors[] = 'La descripció és obligatòria i no pot superar els 2000 caràcters.';
        }
        $start = is_string($input['date_start'] ?? null) ? \DateTime::createFromFormat('!Y-m-d', $input['date_start']) : false;
        if (!$start || $start->format('Y-m-d') !== $input['date_start']) {
            $errors[] = 'La data d\'inici no és vàlida.';
        }
        $end = is_string($input['date_end'] ?? null) ? \DateTime::createFromFormat('!Y-m-d', $input['date_end']) : false;
        if (!$end || $end->format('Y-m-d') !== $input['date_end']) {
            $errors[] = 'La data de finalització no és vàlida.';
        }
        if ($start && $end && $end < $start) {
            $errors[] = 'La data de finalització no pot ser anterior a la d\'inici.';
        }
        return $errors;
    }
}