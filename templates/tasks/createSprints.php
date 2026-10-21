<?php

declare(strict_types=1);
?>
<h1>Nou Esprint</h1>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post">
    <?= csrfField() ?>
    <label class="form-label" for="name">Nom *</label>
    <input class="form-control mb-3" id="name" name="name" maxlength="100" value="<?= h($values['name']) ?>" >
    <label class="form-label" for="description">Descripció *</label>
    <input class="form-control mb-3" id="description" name="description" maxlength="2000" value="<?= h($values['description']) ?>" >
    <label class="form-label" for="date_start">Data d'inici *</label>
    <input class="form-control mb-3" id="date_start" name="date_start" type="date" value="<?= h($values['date_start']) ?>" >
    <label class="form-label" for="date_end">Data de finalització *</label>
    <input class="form-control mb-3" id="date_end" name="date_end" type="date" value="<?= h($values['date_end']) ?>" >
    <label class="form-label" for="state">Estat</label>
    <select class="form-select mb-3" id="state" name="state">
        <option value="pending" <?= $values['state'] === 'pending' ? 'selected' : '' ?>>Pendent</option>
        <option value="in_progress" <?= $values['state'] === 'in_progress' ? 'selected' : '' ?>>En curs</option>
        <option value="completed" <?= $values['state'] === 'completed' ? 'selected' : '' ?>>Completat</option>
    </select>
    <button class="btn btn-primary">Crear sprint</button>
</form>
