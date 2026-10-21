<?php

declare(strict_types=1);
?>
<a href="board.php">← Tauler</a>
<h1><?= h($task['title']) ?> <?= statusBadge($task['status']) ?></h1>
<p><?= nl2br(h($task['description'])) ?></p>
<p>Equip: <?= h($task['team_name'] ?? 'Sense equip') ?> · Responsable: <?= h($task['user_name'] ?? 'Sense responsable') ?> · Esprint: <?= h($task['sprint_name']) ?></p>
<a class="btn btn-outline-primary mb-4" href="task-edit.php?id=<?= (int) $task['id'] ?>">Editar</a>
<h2>Comentaris</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>
<form method="post" class="mb-4">
    <?= csrfField() ?>
    <label class="form-label" for="content">Nou comentari</label>
    <textarea class="form-control mb-2" id="content" name="content" maxlength="2000" required><?= h($content) ?></textarea>
    <button class="btn btn-primary">Afegir comentari</button>
</form>
<?php foreach ($comments as $comment): ?>
<div class="card mb-2"><div class="card-body"><?= nl2br(h($comment['content'])) ?><div class="text-muted small"><?= h($comment['name']) ?> · <?= h($comment['created_at']) ?></div></div></div>
<?php endforeach; ?>
