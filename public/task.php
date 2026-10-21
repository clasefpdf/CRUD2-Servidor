<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$id = positiveId($_GET['id'] ?? null);
$db = getDB();
$stmt = $db->prepare('SELECT t.*, te.name AS team_name, u.name AS user_name, s.name AS sprint_name
    FROM tasks t LEFT JOIN teams te ON te.id = t.team_id LEFT JOIN users u ON u.id = t.user_id
    JOIN sprints s ON s.id = t.sprint_id WHERE t.id = ?
    AND (CAST(? AS INTEGER) = 1 OR EXISTS (SELECT 1 FROM sprint_team_members stm
        WHERE stm.sprint_id = t.sprint_id AND stm.team_id = t.team_id AND stm.user_id = ?))');
$stmt->execute([$id, (int) isTeacher(), $_SESSION['user_id']]);
$task = $stmt->fetch();
if (!$task) {
    http_response_code(404);
    exit('Tasca no trobada.');
}
$error = '';
$content = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $content = is_string($_POST['content'] ?? null) ? trim($_POST['content']) : '';
    if ($content === '' || mb_strlen($content) > 2000) {
        $error = 'El comentari és obligatori i no pot superar els 2000 caràcters.';
    } else {
        $stmt = $db->prepare('INSERT INTO comments (task_id, user_id, content) VALUES (?, ?, ?)');
        $stmt->execute([$id, $_SESSION['user_id'], $content]);
        setFlash('success', 'Comentari afegit correctament.');
        redirect('task.php?id=' . $id);
    }
}
$stmt = $db->prepare('SELECT c.content, c.created_at, u.name FROM comments c JOIN users u ON u.id = c.user_id WHERE c.task_id = ? ORDER BY c.id');
$stmt->execute([$id]);
$comments = $stmt->fetchAll();
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/show.php';
require __DIR__ . '/../includes/footer.php';
