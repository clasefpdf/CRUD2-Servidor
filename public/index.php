<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$sprint = getDB()->query("SELECT * FROM sprints WHERE status = 'active' ORDER BY start_date DESC LIMIT 1")->fetch();
require __DIR__ . '/../includes/header.php';
?>
<h1>Benvingut/da a SprintBoard</h1>
<p>Organitza el treball de l'equip amb esprints i un tauler Kanban.</p>

<?php if ($sprint): ?>
    
<div class="card mb-3"><div class="card-body">
    <h2>Esprint actual: <?= h($sprint['name']) ?> <?= statusBadge($sprint['status']) ?></h2>
    <p><?= h($sprint['goal']) ?></p>
    <a class="btn btn-primary" href="sprint.php?id=<?= (int) $sprint['id'] ?>">Veure esprint</a>
    <a class="btn btn-outline-primary" href="board.php">Obrir tauler</a>
</div></div>
<?php else: ?><p>No hi ha cap esprint actiu.</p><?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
