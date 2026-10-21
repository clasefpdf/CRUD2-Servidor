<?php

declare(strict_types=1);

use App\Repository\WorkShiftRepository;
use App\WorkShift;

require __DIR__ . '/../includes/bootstrap.php';
requireAdmin();

$filters = ['q' => '', 'time' => ''];
$errors = [];
foreach ($filters as $key => $_) {
    $filters[$key] = is_string($_GET[$key] ?? null) ? trim($_GET[$key]) : '';
}
if (mb_strlen($filters['q']) > WorkShift::DESCRIPTION_MAX_LENGTH) {
    $errors[] = 'El texto de búsqueda es demasiado largo.';
    $filters['q'] = '';
}
if ($filters['time'] !== '' && !WorkShift::isValidTime($filters['time'])) {
    $errors[] = 'La hora del filtro no es válida (formato HH:MM).';
    $filters['time'] = '';
}

$shifts = (new WorkShiftRepository(getDB()))->search(
    $filters['q'] !== '' ? $filters['q'] : null,
    $filters['time'] !== '' ? $filters['time'] : null,
);
$now = (new DateTimeImmutable())->format('H:i');

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/shifts/list.php';
require __DIR__ . '/../includes/footer.php';
