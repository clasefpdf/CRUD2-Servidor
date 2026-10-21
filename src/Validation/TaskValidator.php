<?php

declare(strict_types=1);

namespace App\Validation;

final class TaskValidator
{
    /** @return list<string> */
    public function validate(array $input, array $sprintIds, array $teamIds, array $userIds): array
    {
        $errors = [];
        $title = trim((string) ($input['title'] ?? ''));
        if ($title === '' || mb_strlen($title) > 150) {
            $errors[] = 'El títol és obligatori i no pot superar els 150 caràcters.';
        }
        if (mb_strlen(trim((string) ($input['description'] ?? ''))) > 2000) {
            $errors[] = 'La descripció no pot superar els 2000 caràcters.';
        }
        foreach (['sprint_id' => $sprintIds, 'team_id' => $teamIds, 'user_id' => $userIds] as $field => $validIds) {
            $value = $input[$field] ?? '';
            if ($value === '' && $field !== 'sprint_id') {
                continue;
            }
            if (!is_string($value) || !ctype_digit($value) || !in_array((int) $value, $validIds, true)) {
                $errors[] = 'Selecciona un valor vàlid per a ' . $field . '.';
            }
        }
        return $errors;
    }
}
