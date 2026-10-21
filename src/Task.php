<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

final class Task
{
    private string $status;

    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly int $sprintId,
        private readonly ?int $teamId = null,
        private readonly ?int $userId = null,
        string $status = 'todo',
    ) {
        if (trim($title) === '') {
            throw new InvalidArgumentException('Títol de tasca no vàlid.');
        }

        $this->setStatus($status);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSprintId(): int
    {
        return $this->sprintId;
    }

    public function getTeamId(): ?int
    {
        return $this->teamId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, ['todo', 'in_progress', 'done'], true)) {
            throw new InvalidArgumentException('Estat de tasca no vàlid.');
        }

        $this->status = $status;
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            $row['title'],
            $row['description'] ?? '',
            (int) $row['sprint_id'],
            $row['team_id'] === null ? null : (int) $row['team_id'],
            $row['user_id'] === null ? null : (int) $row['user_id'],
            $row['status'],
        );
    }
}
