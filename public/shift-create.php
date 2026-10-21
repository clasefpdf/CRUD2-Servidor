<?php

declare(strict_types=1);
use App\Validation\WorkShiftValidator;
use App\Repository\WorkShiftRepository;
use App\Shift;

require __DIR__ . '/../includes/bootstrap.php';

requireAdmin();
$db = getDB();
$values = ['name' => '', 'start_time' => '', 'end_time' => ''];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new WorkShiftValidator())->validate($values);
    if ($errors === []) {
        $stmt = $db->prepare('SELECT 1 FROM users WHERE name = ?');
        $stmt->execute([$values['name']]);
        if ($stmt->fetch()) {
            $errors[] = 'El torn ja existeix';
        }
    }
        if ($errors === []) {
        $Shift = new Shift(
        $values['name'],
        $values['start_time'],
        $values['end_time'],

    );
    try {
        $stmt = $db->prepare('INSERT INTO shifts (name, start_date, end_date) VALUES (?, ?, ?)');
        $stmt->execute([
            $user->getName(),
            $user->getStartTime(),
            $user->getEndTime(),

    ]);
    } catch (Throwable $exception) {
        applicationLogger()->error('Error al crear un usuari: No s’ha pogut inicialitzar la base de dades.', ['exception' => $exception]);
            http_response_code(500);
            exit('Error intern al crear un usuari. Torna-ho a provar més tard.');
    }

    setFlash('success', 'Torn creat correctament.');
    redirect('shifts.php');
}
}

$heading = 'Nuevo turno';
$submitLabel = 'Crear turno';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/shifts/form.php';
require __DIR__ . '/../includes/footer.php';
    