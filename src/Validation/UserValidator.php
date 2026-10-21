<?php

declare(strict_types=1);

namespace App\Validation;

use App\User;

final class UserValidator
{
    /** @return list<string> */
    public function validate(array $input): array
    {
        $errors = [];

        $name = trim((string) ($input['name'] ?? ''));
        if ($name === '' || mb_strlen($name) > 150) {
            $errors[] = 'El nom és obligatori i no pot superar els 150 caràcters.';
        }

        $username = trim((string) ($input['username'] ?? ''));
        if ($username === '' || mb_strlen($username) > 50) {
            $errors[] = 'L\'usuari és obligatori i no pot superar els 50 caràcters.';
        }

        $email = trim((string) ($input['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255) {
            $errors[] = 'El correu no és vàlid.';
        }

        $password = (string) ($input['password'] ?? '');
        if (mb_strlen($password) < 4) {
            $errors[] = 'La contrasenya ha de tindre almenys 4 caràcters.';
        } elseif (strlen($password) > 72) {
            $errors[] = 'La contrasenya no pot superar els 72 bytes.';
        }

        $role = (string) ($input['role'] ?? '');
        if (!in_array($role, User::ROLES, true)) {
            $errors[] = 'El rol no és vàlid.';
        }
        return $errors;
    }
}