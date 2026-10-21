<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$teams = getDB()->query('SELECT * FROM teams ORDER BY name')->fetchAll();
$students = getDB()->query("SELECT name FROM users WHERE role = 'student' ORDER BY name")->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<h1>Equips</h1>
<ul class="list-group mb-4"><?php foreach ($teams as $team): ?>
    <li class="list-group-item"><a href="team.php?id=<?= (int) $team['id'] ?>"><?= h($team['name']) ?></a></li>
<?php endforeach; ?></ul>
<h2>Alumnat</h2>
<p><?= h(implode(', ', array_column($students, 'name'))) ?></p>
<?php // TODO (alumnat): repartir els estudiants en equips equilibrats sense repetir companys de l'esprint anterior. ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
