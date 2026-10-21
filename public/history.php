<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$sprints = getDB()->query('SELECT id, name, status FROM sprints ORDER BY start_date DESC')->fetchAll();
// TODO (alumnat): mostrar el rol de cada alumne i les tasques completades en cada esprint.
require __DIR__ . '/../includes/header.php';
?>
<h1>Històric d'esprints</h1>
<ul><?php foreach ($sprints as $sprint): ?>
    <li><a href="sprint.php?id=<?= (int) $sprint['id'] ?>"><?= h($sprint['name']) ?></a> <?= statusBadge($sprint['status']) ?></li>
<?php endforeach; ?></ul>
<p class="text-muted">El detall històric està pendent.</p>
<?php require __DIR__ . '/../includes/footer.php'; ?>
