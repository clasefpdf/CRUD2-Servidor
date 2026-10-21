<?php

declare(strict_types=1);

use App\Task;
use App\Validation\TaskValidator;

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$db = getDB();
$sprints = $db->query('SELECT id, name FROM sprints ORDER BY start_date DESC')->fetchAll();
$stmt = $db->prepare('SELECT DISTINCT te.id, te.name FROM teams te
    JOIN sprint_team_members stm ON stm.team_id = te.id
    WHERE CAST(? AS INTEGER) = 1 OR stm.user_id = ? ORDER BY te.name');
$stmt->execute([(int) isTeacher(), $_SESSION['user_id']]);
$teams = $stmt->fetchAll();
$users = $db->query('SELECT id, name FROM users ORDER BY name')->fetchAll();
$values = ['title' => '', 'description' => '', 'sprint_id' => '', 'team_id' => '', 'user_id' => ''];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new TaskValidator())->validate(
        $values,
        array_map('intval', array_column($sprints, 'id')),
        array_map('intval', array_column($teams, 'id')),
        array_map('intval', array_column($users, 'id')),
    );
    if ($errors === [] && $values['team_id'] !== '') {
        $stmt = $db->prepare('SELECT 1 FROM sprint_team_members WHERE sprint_id = ? AND team_id = ?');
        $stmt->execute([(int) $values['sprint_id'], (int) $values['team_id']]);
        if (!$stmt->fetch()) {
            $errors[] = 'L’equip no participa en este esprint.';
        }
    }
    if ($errors === [] && !isTeacher()) {
        $stmt = $db->prepare('SELECT 1 FROM sprint_team_members
            WHERE sprint_id = ? AND team_id = ? AND user_id = ?');
        $stmt->execute([(int) $values['sprint_id'], (int) $values['team_id'], $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            $errors[] = 'Selecciona un equip del qual formes part en este esprint.';
        }
    }
    if ($errors === [] && $values['team_id'] !== '' && $values['user_id'] !== '') {
        $stmt = $db->prepare('SELECT 1 FROM sprint_team_members WHERE sprint_id = ? AND team_id = ? AND user_id = ?');
        $stmt->execute([(int) $values['sprint_id'], (int) $values['team_id'], (int) $values['user_id']]);
        if (!$stmt->fetch()) {
            $errors[] = 'El responsable ha de pertànyer a l’equip en este esprint.';
        }
    }
    if ($errors === []) {
        $task = new Task(
            $values['title'], $values['description'], (int) $values['sprint_id'],
            $values['team_id'] === '' ? null : (int) $values['team_id'],
            $values['user_id'] === '' ? null : (int) $values['user_id'],
        );
        try {
            $stmt = $db->prepare('INSERT INTO tasks (title, description, status, sprint_id, team_id, user_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $task->getTitle(), $task->getDescription(), $task->getStatus(),
            $task->getSprintId(), $task->getTeamId(), $task->getUserId(),
        ]);
        }
        catch (Throwable $exception){
            applicationLogger()->error('Error al crear una tasca: No s’ha pogut inicialitzar la base de dades.', ['exception' => $exception]);
            http_response_code(500);
            exit('Error intern. Torna-ho a provar més tard.');
        }
        
        setFlash('success', 'Tasca creada correctament.');
        redirect('board.php');
    }
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/create.php';
require __DIR__ . '/../includes/footer.php';
