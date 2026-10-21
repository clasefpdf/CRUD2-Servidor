<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$db = getDB();
$isTeacher = isTeacher();
$stmt = $db->prepare('SELECT DISTINCT te.id, te.name FROM teams te
    LEFT JOIN sprint_team_members stm ON stm.team_id = te.id
    WHERE CAST(? AS INTEGER) = 1 OR stm.user_id = ? ORDER BY te.name');
$stmt->execute([(int) $isTeacher, $_SESSION['user_id']]);
$teams = $stmt->fetchAll();
$teamIds = array_map('intval', array_column($teams, 'id'));
$teamFilter = $_GET['team'] ?? '';
if (!is_string($teamFilter) || ($teamFilter !== '' && !in_array(positiveId($teamFilter), $teamIds, true))) {
    http_response_code(400);
    exit('Filtre d’equip no vàlid.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $taskId = positiveId($_POST['task_id'] ?? null);
    $newStatus = $_POST['new_status'] ?? null;
    if (!$taskId || !in_array($newStatus, ['todo', 'in_progress', 'done'], true)) {
        setFlash('error', 'Tasca o estat no vàlid.');
    } else {
        $stmt = $db->prepare('UPDATE tasks SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?
            AND (CAST(? AS INTEGER) = 1 OR EXISTS (SELECT 1 FROM sprint_team_members stm
                WHERE stm.sprint_id = tasks.sprint_id AND stm.team_id = tasks.team_id AND stm.user_id = ?))');
        $stmt->execute([$newStatus, $taskId, (int) $isTeacher, $_SESSION['user_id']]);
        setFlash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'Estat actualitzat.' : 'Tasca no trobada.');
    }
    redirect('board.php' . ($teamFilter !== '' ? '?team=' . $teamFilter : ''));
}

$sql = 'SELECT t.*, te.name AS team_name, u.name AS user_name FROM tasks t
    LEFT JOIN teams te ON te.id = t.team_id LEFT JOIN users u ON u.id = t.user_id
    WHERE (CAST(? AS INTEGER) = 1 OR EXISTS (SELECT 1 FROM sprint_team_members stm
        WHERE stm.sprint_id = t.sprint_id AND stm.team_id = t.team_id AND stm.user_id = ?))';
$params = [(int) $isTeacher, $_SESSION['user_id']];
if ($teamFilter !== '') {
    $sql .= ' AND t.team_id = ?';
    $params[] = (int) $teamFilter;
}
$sql .= ' ORDER BY t.id DESC';
$stmt = $db->prepare($sql);
$stmt->execute($params);
$board = ['todo' => [], 'in_progress' => [], 'done' => []];
foreach ($stmt->fetchAll() as $task) {
    $board[$task['status']][] = $task;
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/board.php';
require __DIR__ . '/../includes/footer.php';
