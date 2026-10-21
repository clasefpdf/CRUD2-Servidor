<?php
declare(strict_types=1);
use App\Task;
use App\Validation\TaskValidator;

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();

$errors = [];
$id = positiveId($_GET['id'] ?? null);
$db = getDB();
if ($id === null) {
    http_response_code(404);
    exit('Tasca no trobada.');
}
$sprints = getDB()->query('SELECT * FROM sprints ORDER BY name')->fetchAll();
$teams = getDB()->query('SELECT * FROM teams ORDER BY name')->fetchAll();
$users = getDB()->query('SELECT * FROM users ORDER BY name')->fetchAll();
$stmt = getDB()->prepare('SELECT id FROM tasks t WHERE id = ?
    AND (CAST(? AS INTEGER) = 1 OR EXISTS (SELECT 1 FROM sprint_team_members stm
        WHERE stm.sprint_id = t.sprint_id AND stm.team_id = t.team_id AND stm.user_id = ?))');
$stmt->execute([$id, (int) isTeacher(), $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    http_response_code(404);
    exit('Tasca no trobada.');
}
$stmt = $db->prepare('SELECT * FROM tasks WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
$currentStatus = $row['status'];
$values = [
    'title' => (string) $row['title'],
    'description' => (string) $row['description'],
    'sprint_id' => (string) $row['sprint_id'],
    'team_id' => $row['team_id'] === null ? '' : (string) $row['team_id'],
    'user_id' => $row['user_id'] === null ? '' : (string) $row['user_id'],
];
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

    if ($errors === []) {
        $task = new Task(
            $values['title'],
            $values['description'],
            (int) $values['sprint_id'],
            $values['team_id'] === '' ? null : (int) $values['team_id'],
            $values['user_id'] === '' ? null : (int) $values['user_id'],
            $currentStatus
            );
        $stmt = $db->prepare('UPDATE tasks SET title = ?, description = ?, sprint_id = ?, team_id = ?, user_id = ? WHERE id = ?');        
        $stmt->execute([
            $task->getTitle(),
            $task->getDescription(),
            $task->getSprintId(),
            $task->getTeamId(),
            $task->getUserId(),
            $id
        ]);
        setFlash('success', 'Tasca actualitzada correctament.');
        redirect('board.php');
    }
}
// TODO (alumnat): carregar les dades actuals, validar el formulari d'edició i guardar els canvis amb SQL preparat i CSRF.
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/edit.php';
require __DIR__ . '/../includes/footer.php';
