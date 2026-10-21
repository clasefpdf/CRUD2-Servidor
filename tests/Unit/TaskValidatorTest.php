<?php

declare(strict_types=1);

use App\Validation\TaskValidator;
use PHPUnit\Framework\TestCase;

final class TaskValidatorTest extends TestCase
{
    public function testValidTaskNeedsNoOptionalAssignments(): void
    {
        $input = ['title' => 'Preparar el tauler', 'description' => '', 'sprint_id' => '1', 'team_id' => '', 'user_id' => ''];

        $errors = (new TaskValidator())->validate($input, [1], [2], [3]);

        self::assertSame([], $errors);
    }

    public function testTitleLengthBoundary(): void
    {
        $validator = new TaskValidator();

        $valid = $validator->validate(['title' => str_repeat('a', 150), 'sprint_id' => '1'], [1], [], []);
        $invalid = $validator->validate(['title' => str_repeat('a', 151), 'sprint_id' => '1'], [1], [], []);

        self::assertSame([], $valid);
        self::assertContains('El títol és obligatori i no pot superar els 150 caràcters.', $invalid);
    }

    public function testUnknownSprintAndTeamAreRejected(): void
    {
        $input = ['title' => 'Preparar el tauler', 'sprint_id' => '99', 'team_id' => '99'];

        $errors = (new TaskValidator())->validate($input, [1], [2], [3]);

        self::assertCount(2, $errors);
        self::assertContains('Selecciona un valor vàlid per a sprint_id.', $errors);
        self::assertContains('Selecciona un valor vàlid per a team_id.', $errors);
    }
}
