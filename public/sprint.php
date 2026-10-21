<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$id = positiveId($_GET['id'] ?? null);
$stmt = getDB()->prepare('SELECT * FROM sprints WHERE id = ?');
$stmt->execute([$id]);
$sprint = $stmt->fetch();
if (!$sprint) {
    http_response_code(404);
    exit('Esprint no trobat.');
}
$stmt = getDB()->prepare('SELECT t.id, t.title, t.status FROM tasks t WHERE t.sprint_id = ?
    AND (CAST(? AS INTEGER) = 1 OR EXISTS (SELECT 1 FROM sprint_team_members stm
        WHERE stm.sprint_id = t.sprint_id AND stm.team_id = t.team_id AND stm.user_id = ?))
    ORDER BY t.id DESC');
$stmt->execute([$id, (int) isTeacher(), $_SESSION['user_id']]);
$tasks = $stmt->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<a href="sprints.php">← Esprints</a>
<h1><?= h($sprint['name']) ?> <?= statusBadge($sprint['status']) ?></h1><a href="sprint-create.php">Crear Esprint</a>
<p><strong>Dates:</strong> <?= h($sprint['start_date']) ?> — <?= h($sprint['end_date']) ?></p>
<p><strong>Objectiu:</strong> <?= h($sprint['goal']) ?></p>
<h2>Tasques</h2>
<ul><?php foreach ($tasks as $task): ?>
    <li><a href="task.php?id=<?= (int) $task['id'] ?>"><?= h($task['title']) ?></a> <?= statusBadge($task['status']) ?></li>
<?php endforeach; ?></ul>
<?php require __DIR__ . '/../includes/footer.php'; ?>
