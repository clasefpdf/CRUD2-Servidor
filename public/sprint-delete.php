<?php
declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
if (!isTeacher()) {
    http_response_code(403);
    exit('Accés denegat.');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Mètode no permés.');
}
requireCsrfToken();

$id = positiveId($_POST['id'] ?? null);
if ($id === null) {
    http_response_code(404);
    exit('Sprint no trobat.');
}

$db = getDB();
$stmt = $db->prepare('SELECT name FROM sprints WHERE id = ?');
$stmt->execute([$id]);
$sprint = $stmt->fetch();
if (!$sprint) {
    http_response_code(404);
    exit('Sprint no trobat.');
}

$stmt = $db->prepare('SELECT COUNT(*) FROM tasks WHERE sprint_id = ?');
$stmt->execute([$id]);
$tasks = (int) $stmt->fetchColumn();

$stmt = $db->prepare('SELECT COUNT(*) FROM sprint_team_members WHERE sprint_id = ?');
$stmt->execute([$id]);
$members = (int) $stmt->fetchColumn();

if ($tasks > 0 || $members > 0) {
    $reasons = [];
    if ($tasks > 0) {
        $reasons[] = $tasks . ' tasques';
    }
    if ($members > 0) {
        $reasons[] = $members . ' assignacions d\'equip';
    }
    setFlash('error', 'No es pot esborrar el sprint: té ' . implode(' i ', $reasons) . ' vinculades.');
    redirect('sprint.php?id=' . $id);
}

// Les condicions es repeteixen al DELETE perquè siga atòmic davant canvis simultanis.
$stmt = $db->prepare('DELETE FROM sprints WHERE id = ?
    AND NOT EXISTS (SELECT 1 FROM tasks WHERE sprint_id = sprints.id)
    AND NOT EXISTS (SELECT 1 FROM sprint_team_members WHERE sprint_id = sprints.id)');
$stmt->execute([$id]);

if ($stmt->rowCount() === 0) {
    setFlash('error', 'No s\'ha pogut esborrar el sprint perquè ha obtingut dades vinculades.');
    redirect('sprint.php?id=' . $id);
}

setFlash('success', 'Sprint esborrat correctament.');
redirect('sprints.php');