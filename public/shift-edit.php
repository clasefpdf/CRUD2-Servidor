<?php

declare(strict_types=1);

use App\Exception\DuplicateWorkShiftException;
use App\Exception\WorkShiftNotFoundException;
use App\Repository\WorkShiftRepository;
use App\Validation\WorkShiftValidator;
use App\WorkShift;

require __DIR__ . '/../includes/bootstrap.php';
requireAdmin();

$id = positiveId($_GET['id'] ?? null);
if ($id === null) {
    http_response_code(404);
    exit('Turno no encontrado.');
}

$repository = new WorkShiftRepository(getDB());
try {
    $shift = $repository->find($id);
} catch (WorkShiftNotFoundException) {
    http_response_code(404);
    exit('Turno no encontrado.');
}

$values = [
    'description' => $shift->getDescription(),
    'start_time' => $shift->getStartTime(),
    'end_time' => $shift->getEndTime(),
];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new WorkShiftValidator())->validate($values);
    if ($errors === []) {
        try {
            $repository->update(
                $id,
                new WorkShift($values['description'], $values['start_time'], $values['end_time'])
            );
            setFlash('success', 'Turno actualizado correctamente.');
            redirect('shifts.php');
        } catch (WorkShiftNotFoundException) { // esborrat per un altre usuari mentre s'editava
            setFlash('error', 'El turno ya no existe.');
            redirect('shifts.php');
        } catch (DuplicateWorkShiftException $exception) {
            $errors[] = $exception->getMessage();
        } catch (Throwable $exception) {
            applicationLogger()->error('Error al actualizar un turno de trabajo.', ['exception' => $exception]);
            http_response_code(500);
            exit('Error interno. Inténtalo de nuevo más tarde.');
        }
    }
}

$heading = 'Editar turno';
$submitLabel = 'Guardar cambios';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/shifts/form.php';
require __DIR__ . '/../includes/footer.php';
