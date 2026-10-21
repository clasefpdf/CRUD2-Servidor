<?php

declare(strict_types=1);
// Llistat de torns. Variables: $shifts (WorkShift[]), $filters, $errors, $now ("HH:MM").
$formatDuration = static function (int $minutes): string {
    $hours = intdiv($minutes, 60);
    $rest = $minutes % 60;

    return $hours . ' h' . ($rest > 0 ? ' ' . $rest . ' min' : '');
};
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <p>.../ Departamentos / <a href="/../shifts.php">Turnos de trabajo</a></p>
    <h1 class="mb-0">Turnos de trabajo</h1>
    <a class="btn btn-primary" href="shift-create.php">Nuevo turno</a>
</div>
<?php if ($errors): ?><div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="get" class="row g-2 align-items-end mb-4" role="search" aria-label="Filtrar turnos">
    <div class="col-12 col-md-5">
        <label class="form-label" for="q">Descripción contiene</label>
        <input class="form-control" id="q" name="q" maxlength="100" value="<?= h($filters['q']) ?>">
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label" for="time">Activo a las</label>
        <input class="form-control" id="time" name="time" type="time" value="<?= h($filters['time']) ?>">
    </div>
    <div class="col-12 col-md-4 d-flex gap-2">
        <button class="btn btn-secondary">Filtrar</button>
        <a class="btn btn-outline-secondary" href="shifts.php">Limpiar</a>
    </div>
</form>
<?php if ($shifts === []): ?>
    <p class="text-muted">No hay turnos que coincidan con el filtro.</p>
<?php else: ?>
<div class="table-responsive">
<table class="table table-striped align-middle">
    <caption class="visually-hidden">Listado de turnos de trabajo</caption>
    <thead><tr>
        <th scope="col">Descripción</th>
        <th scope="col">Inicio</th>
        <th scope="col">Fin</th>
        <th scope="col">Duración</th>
        <th scope="col"><span class="visually-hidden">Estado</span></th>
        <th scope="col" class="text-end">Acciones</th>
    </tr></thead>
    <tbody>
    <?php foreach ($shifts as $shift): ?>
        <tr>
            <th scope="row"><?= h($shift->getDescription()) ?></th>
            <td><?= h($shift->getStartTime()) ?></td>
            <td><?= h($shift->getEndTime()) ?><?= $shift->crossesMidnight() ? ' <small class="text-muted">(día siguiente)</small>' : '' ?></td>
            <td><?= h($formatDuration($shift->durationMinutes())) ?></td>
            <td><?= $shift->covers($now) ? '<span class="badge bg-success">Turno actual</span>' : '' ?></td>
            <td class="text-end">
                <div class="d-inline-flex gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="shift-edit.php?id=<?= (int) $shift->getId() ?>" aria-label="Editar turno <?= h($shift->getDescription()) ?>">Editar</a>
                    <form method="post" action="shift-delete.php" class="m-0" onsubmit="return confirm('¿Seguro que quieres eliminar este turno?');">
                        <?= csrfField() ?>
                        <input type="hidden" name="id" value="<?= (int) $shift->getId() ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar turno <?= h($shift->getDescription()) ?>">Eliminar</button>
                    </form>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
