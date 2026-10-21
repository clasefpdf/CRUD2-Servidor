<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

final class User
{
    public const ROLES = ['student', 'teacher'];

    public function __construct(
        private readonly string $name,
        private readonly string $username,
        private readonly string $email,
        private readonly string $passwordHash,
        private readonly string $team_id,
        private readonly string $role = 'student',
    ) {
        if (trim($name) === '') {
            throw new InvalidArgumentException('Nom d\'usuari no vàlid.');
        }
        if (trim($username) === '') {
            throw new InvalidArgumentException('Usuari no vàlid.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Correu no vàlid.');
        }
        if (!in_array($role, self::ROLES, true)) {
            throw new InvalidArgumentException('Rol no vàlid.');
        }
    }
    public static function register(
        string $name,
        string $username,
        string $email,
        string $plainPassword,
        string $team,
        string $role = 'student',
    ): self {
        return new self($name, $username, $email, password_hash($plainPassword, PASSWORD_DEFAULT), $team ,$role);
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self($row['name'], $row['username'], $row['email'], $row['password'],$row['role']);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRole(): string
    {
        return $this->role;
    }
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }
}