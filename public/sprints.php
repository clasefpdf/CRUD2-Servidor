<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$sprints = getDB()->query('SELECT * FROM sprints ORDER BY start_date DESC')->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<h1>Esprints</h1>
<div class="list-group">
<?php foreach ($sprints as $sprint): ?>
    <div class="list-group-item d-flex justify-content-between align-items-center">
        <a class="text-decoration-none text-reset flex-grow-1" href="sprint.php?id=<?= (int) $sprint['id'] ?>">
            <strong><?= h($sprint['name']) ?></strong> <?= statusBadge($sprint['status']) ?>
            <span class="d-block text-muted"><?= h($sprint['start_date']) ?> — <?= h($sprint['end_date']) ?></span>
        </a>
        <?php if (isTeacher()): ?>
        <div class="d-flex flex-column gap-2 ms-3" style="width: 110px;">
            <a class="btn btn-sm btn-outline-secondary" href="sprint-edit.php?id=<?= (int) $sprint['id'] ?>">Editar</a>
            <form method="post" action="sprint-delete.php" onsubmit="return confirm('Segur que vols eliminar aquest esprint?');">
                <?= csrfField() ?>
                <input type="hidden" name="id" value="<?= (int) $sprint['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Eliminar</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>