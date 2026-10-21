<?php

declare(strict_types=1);
// Formulari compartit per a crear i editar torns. Variables: $heading, $submitLabel, $values, $errors.
?>
<h1><?= h($heading) ?></h1>
<?php if ($errors): ?><div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" novalidate>
    <?= csrfField() ?>
    <label class="form-label" for="name">Nombre del Turno*</label>
    <input class="form-control mb-3" id="name" name="name" maxlength="100" required value="<?= h($values['name
    ']) ?>">
    <label class="form-label" for="start_time">Hora de inicio *</label>
    <input class="form-control mb-3" id="start_time" name="start_time" type="time" required value="<?= h($values['start_time']) ?>">
    <label class="form-label" for="end_time">Hora de fin *</label>
    <input class="form-control" id="end_time" name="end_time" type="time" required aria-describedby="end_time_help" value="<?= h($values['end_time']) ?>">
    <div id="end_time_help" class="form-text mb-3">Si la hora de fin es anterior a la de inicio, el turno termina al día siguiente (por ejemplo, 22:00 – 06:00)</div>
    <button class="btn btn-primary"><?= h($submitLabel) ?></button>
    <a class="btn btn-outline-secondary" href="shifts.php">Cancelar</a>
</form>
