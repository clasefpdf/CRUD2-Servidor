<?php

declare(strict_types=1);
?>
<h1>Tauler Kanban</h1>
<form method="get" class="mb-4">
    <label for="team" class="form-label">Filtrar per equip</label>
    <div class="d-flex gap-2"><select id="team" name="team" class="form-select w-auto">
        <option value="">Tots els equips</option>
        <?php foreach ($teams as $team): ?>
        <option value="<?= (int) $team['id'] ?>" <?= (string) $team['id'] === $teamFilter ? 'selected' : '' ?>><?= h($team['name']) ?></option>
        <?php endforeach; ?>
    </select><button class="btn btn-primary">Filtrar</button></div>
</form>
<?php // TODO (alumnat): add filters for status and assignee. ?>
<div class="row g-3">
<?php foreach (['todo' => 'To do', 'in_progress' => 'In progress', 'done' => 'Done'] as $status => $label): ?>
    <section class="col-lg-4"><div class="card h-100"><div class="card-header"><h2 class="h5 m-0"><?= h($label) ?></h2></div>
    <div class="card-body">
    <?php if (!$board[$status]): ?><p class="text-muted">No hi ha tasques.</p><?php endif; ?>
    <?php foreach ($board[$status] as $task): ?>
        <article class="card mb-3"><div class="card-body">
            <h3 class="h6"><a href="task.php?id=<?= (int) $task['id'] ?>"><?= h($task['title']) ?></a></h3>
            <p class="small"><?= h(mb_strimwidth($task['description'], 0, 100, '…')) ?></p>
            <p class="small">Equip: <?= h($task['team_name'] ?? 'Sense equip') ?><br>Responsable: <?= h($task['user_name'] ?? 'Sense responsable') ?><br>Estat: <?= statusBadge($task['status']) ?></p>
            <form method="post">
                <?= csrfField() ?>
                <input type="hidden" name="task_id" value="<?= (int) $task['id'] ?>">
                <label class="visually-hidden" for="status-<?= (int) $task['id'] ?>">Canviar estat</label>
                <select class="form-select form-select-sm mb-2" id="status-<?= (int) $task['id'] ?>" name="new_status">
                <?php foreach (['todo' => 'To do', 'in_progress' => 'In progress', 'done' => 'Done'] as $value => $name): ?>
                    <option value="<?= h($value) ?>" <?= $value === $status ? 'selected' : '' ?>><?= h($name) ?></option>
                <?php endforeach; ?></select>
                <button class="btn btn-sm btn-outline-primary">Canviar estat</button>
                <a class="btn btn-sm btn-outline-secondary" href="task-edit.php?id=<?= (int) $task['id'] ?>">Editar</a>
            </form>
        </div></article>
    <?php endforeach; ?>
    </div></div></section>
<?php endforeach; ?>
</div>
