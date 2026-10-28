<?php

declare(strict_types=1);

use App\Exception\DuplicateWorkShiftException;
use App\Repository\WorkShiftRepository;
use App\Validation\WorkShiftValidator;
use App\WorkShift;

require __DIR__ . '/../includes/bootstrap.php';
requireAdmin();

$values = ['name' => '', 'start_time' => '', 'end_time' => ''];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new WorkShiftValidator())->validate($values);
    if ($errors === []) {
        try {
            (new WorkShiftRepository(getDB()))->create(
                new WorkShift($values['name'], $values['start_time'], $values['end_time'])
            );
            setFlash('success', 'Turno creado correctamente.');
            redirect('work_shifts.php');
        } catch (DuplicateWorkShiftException $exception) {
            $errors[] = $exception->getMessage();
        } catch (Throwable $exception) {
            applicationLogger()->error('Error al crear un turno de trabajo.', ['exception' => $exception]);
            http_response_code(500);
            exit('Error interno. Inténtalo de nuevo más tarde.');
        }
    }
}

$heading = 'Nuevo turno';
$submitLabel = 'Crear turno';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/work_shifts/form.php';
require __DIR__ . '/../includes/footer.php';