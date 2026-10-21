<?php

declare(strict_types=1);
?>
<h1>Editar tasca </h1>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post">
    <?= csrfField() ?>
    <label class="form-label" for="title">Títol *</label>
    <input class="form-control mb-3" id="title" name="title" maxlength="150" value="<?= h($values['title']) ?>" >
    <label class="form-label" for="description">Descripció</label>
    <textarea class="form-control mb-3" id="description" name="description" maxlength="2000" rows="3"><?= h($values['description']) ?></textarea>
    <label class="form-label" for="sprint_id">Esprint *</label>
    <select class="form-select mb-3" id="sprint_id" name="sprint_id"><option value="">Selecciona un esprint</option>
    <?php foreach ($sprints as $sprint): ?><option value="<?= (int) $sprint['id'] ?>" <?= $values['sprint_id'] === (string) $sprint['id'] ? 'selected' : '' ?>><?= h($sprint['name']) ?></option><?php endforeach; ?>
    </select>
    <label class="form-label" for="team_id">Equip</label>
    <select class="form-select mb-3" id="team_id" name="team_id"><option value="">Sense equip</option>
    <?php foreach ($teams as $team): ?><option value="<?= (int) $team['id'] ?>" <?= $values['team_id'] === (string) $team['id'] ? 'selected' : '' ?>><?= h($team['name']) ?></option><?php endforeach; ?>
    </select>
    <label class="form-label" for="user_id">Responsable</label>
    <select class="form-select mb-3" id="user_id" name="user_id"><option value="">Sense responsable</option>
    <?php foreach ($users as $user): ?><option value="<?= (int) $user['id'] ?>" <?= $values['user_id'] === (string) $user['id'] ? 'selected' : '' ?>><?= h($user['name']) ?></option><?php endforeach; ?>
    </select>
    <button class="btn btn-primary">Guardar canvis</button>
</form>
