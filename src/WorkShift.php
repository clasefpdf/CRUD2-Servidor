<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

final class WorkShift
{
    public const NAME_MAX_LENGTH = 100;

    private const TIME_PATTERN = '/^([01]\d|2[0-3]):[0-5]\d$/';

    public function __construct(
        private readonly string $name,
        private readonly string $startTime,
        private readonly string $endTime,
        private readonly ?int $id = null,
    ) {
        if (trim($name) === '' || mb_strlen($name) > self::NAME_MAX_LENGTH) {
            throw new InvalidArgumentException('Nombre del turno no válido.');
        }
        if (!self::isValidTime($startTime) || !self::isValidTime($endTime)) {
            throw new InvalidArgumentException('Hora del turno no válida (formato HH:MM).');
        }
        if ($startTime === $endTime) {
            throw new InvalidArgumentException('La hora de inicio y la de fin no pueden coincidir.');
        }
    }

    public static function isValidTime(string $time): bool
    {
        return preg_match(self::TIME_PATTERN, $time) === 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /* El torn acaba al dia següent de l'inici. */
    public function crossesMidnight(): bool
    {
        return $this->endTime < $this->startTime;
    }

    /* Minuts planificats del torn (base de la disponibilitat de l'OEE: 8 h = 480 min). */
    public function durationMinutes(): int
    {
        $minutes = self::toMinutes($this->endTime) - self::toMinutes($this->startTime);

        return $minutes > 0 ? $minutes : $minutes + 24 * 60;
    }

    /* L'interval és [inici, fi): a les 14:00 ja és el torn següent. */
    public function covers(string $time): bool
    {
        if (!self::isValidTime($time)) {
            throw new InvalidArgumentException('Hora no válida (formato HH:MM).');
        }
        $t = self::toMinutes($time);
        $start = self::toMinutes($this->startTime);
        $end = self::toMinutes($this->endTime);

        return $start < $end ? ($t >= $start && $t < $end) : ($t >= $start || $t < $end);
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (string) $row['name'],
            (string) $row['start_time'],
            (string) $row['end_time'],
            (int) $row['id'],
        );
    }

    private static function toMinutes(string $time): int
    {
        return (int) substr($time, 0, 2) * 60 + (int) substr($time, 3, 2);
    }
}