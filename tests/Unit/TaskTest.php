<?php

declare(strict_types=1);

use App\Task;
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    public function testStatusCanChange(): void
    {
        $task = new Task('Preparar el tauler', '', 1);

        $task->setStatus('done');

        self::assertSame('done', $task->getStatus());
    }

    public function testInvalidStatusDoesNotChangeTask(): void
    {
        $task = new Task('Preparar el tauler', '', 1);

        try {
            $task->setStatus('cancelled');
            self::fail('An invalid status should throw InvalidArgumentException.');
        } catch (InvalidArgumentException $exception) {
            self::assertSame('todo', $task->getStatus());
        }
    }

    public function testBlankTitleIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Task('   ', '', 1);
    }
}
