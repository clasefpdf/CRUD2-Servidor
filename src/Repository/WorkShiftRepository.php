<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DuplicateWorkShiftException;
use App\Exception\WorkShiftInUseException;
use App\Exception\WorkShiftNotFoundException;
use App\WorkShift;
use PDO;
use PDOException;

/**
 * Tot l'accés a dades dels torns viu ací; les pàgines no escriuen SQL de torns.
 */
final class WorkShiftRepository
{
    public function __construct(private readonly PDO $db)
    {
    }

    public function search(?string $text = null, ?string $time = null): array
    {
        $rows = $this->db
            ->query('SELECT id, description, start_time, end_time FROM work_shifts ORDER BY start_time, description')
            ->fetchAll();
        $shifts = array_map([WorkShift::class, 'fromDatabaseRow'], $rows);

        $text = $text === null ? '' : trim($text);
        if ($text !== '') {
            $shifts = array_filter($shifts, static fn (WorkShift $s): bool => mb_stripos($s->getDescription(), $text) !== false);
        }
        if ($time !== null && $time !== '') {
            $shifts = array_filter($shifts, static fn (WorkShift $s): bool => $s->covers($time));
        }

        return array_values($shifts);
    }

    /** @throws WorkShiftNotFoundException */
    public function find(int $id): WorkShift
    {
        $stmt = $this->db->prepare('SELECT id, description, start_time, end_time FROM work_shifts WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw WorkShiftNotFoundException::withId($id);
        }

        return WorkShift::fromDatabaseRow($row);
    }

    public function findCurrent(string $time): ?WorkShift
    {
        return $this->search(null, $time)[0] ?? null;
    }

    /** @throws DuplicateWorkShiftException */
    public function create(WorkShift $shift): int
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO work_shifts (description, start_time, end_time) VALUES (?, ?, ?)');
            $stmt->execute([$shift->getDescription(), $shift->getStartTime(), $shift->getEndTime()]);
        } catch (PDOException $e) {
            if ($this->isConstraint($e, 'UNIQUE')) {
                throw DuplicateWorkShiftException::forDescription($shift->getDescription(), $e);
            }
            throw $e;
        }

        return (int) $this->db->lastInsertId();
    }

    /**
     * @throws WorkShiftNotFoundException
     * @throws DuplicateWorkShiftException
     */
    public function update(int $id, WorkShift $shift): void
    {
        try {
            $stmt = $this->db->prepare('UPDATE work_shifts SET description = ?, start_time = ?, end_time = ? WHERE id = ?');
            $stmt->execute([$shift->getDescription(), $shift->getStartTime(), $shift->getEndTime(), $id]);
        } catch (PDOException $e) {
            if ($this->isConstraint($e, 'UNIQUE')) {
                throw DuplicateWorkShiftException::forDescription($shift->getDescription(), $e);
            }
            throw $e;
        }
        if ($stmt->rowCount() === 0) {
            throw WorkShiftNotFoundException::withId($id);
        }
    }

    /**
     * Si una altra taula (departaments, historial de sessions...) referencia el torn
     * amb clau forana sense CASCADE, SQLite rebutja l'esborrament i ho traduïm.
     *
     * @throws WorkShiftNotFoundException
     * @throws WorkShiftInUseException
     */
    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM work_shifts WHERE id = ?');
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            if ($this->isConstraint($e, 'FOREIGN KEY')) {
                throw WorkShiftInUseException::withId($id, $e);
            }
            throw $e;
        }
        if ($stmt->rowCount() === 0) {
            throw WorkShiftNotFoundException::withId($id);
        }
    }

    private function isConstraint(PDOException $e, string $kind): bool
    {
        return ($e->errorInfo[0] ?? null) === '23000' && str_contains($e->getMessage(), $kind);
    }
}
