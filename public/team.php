<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$id = positiveId($_GET['id'] ?? null);
$stmt = getDB()->prepare('SELECT * FROM teams WHERE id = ?');
$stmt->execute([$id]);
$team = $stmt->fetch();
if (!$team) {
    http_response_code(404);
    exit('Equip no trobat.');
}
$stmt = getDB()->prepare("SELECT u.name, stm.role FROM sprint_team_members stm
    JOIN users u ON u.id = stm.user_id
    JOIN sprints s ON s.id = stm.sprint_id AND s.status = 'active'
    WHERE stm.team_id = ? ORDER BY u.name");
$stmt->execute([$id]);
$members = $stmt->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<a href="teams.php">← Equips</a><h1><?= h($team['name']) ?></h1>
<h2>Membres i rols de l'esprint actual</h2>
<ul class="list-group"><?php foreach ($members as $member): ?>
    <li class="list-group-item"><?= h($member['name']) ?> — <?= h($member['role'] ?? 'Sense rol assignat') ?></li>
<?php endforeach; ?></ul>
<?php // TODO (alumnat): formulari per canviar rols i validar que sols hi haja un Scrum Master per equip i esprint. ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
