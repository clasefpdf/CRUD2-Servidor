<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

final class Sprint
{

    public function __construct(
        private readonly string $name,
        private readonly string $description,
        private readonly \DateTime $dateStart,
        private readonly \DateTime $dateEnd,
        private readonly String $state,
    ) {
        if (trim($name) === '') {
            throw new InvalidArgumentException('Nom del sprint.');
        }
        if ($dateEnd < $dateStart) {
            throw new InvalidArgumentException('La data de finalització no pot ser anterior a la d\'inici.');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            $row['name'],
            $row['description'] ?? '',
            new \DateTime($row['date_start']),
            new \DateTime($row['date_end']),
            $row['state'] ?? 'pending',
        );
    }
}
