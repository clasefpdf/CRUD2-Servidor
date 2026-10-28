<?php

declare(strict_types=1);

use App\Exception\WorkShiftInUseException;
use App\Exception\WorkShiftNotFoundException;
use App\Repository\WorkShiftRepository;

require __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}
requireCsrfToken();

$id = positiveId($_POST['id'] ?? null);
if ($id === null) {
    http_response_code(404);
    exit('Turno no encontrado.');
}

try {
    (new WorkShiftRepository(getDB()))->delete($id);
    setFlash('success', 'Turno eliminado correctamente.');
} catch (WorkShiftNotFoundException | WorkShiftInUseException $exception) {
    setFlash('error', $exception->getMessage());
} catch (Throwable $exception) {
    applicationLogger()->error('Error al eliminar un turno de trabajo.', ['exception' => $exception]);
    http_response_code(500);
    exit('Error interno. Inténtalo de nuevo más tarde.');
}
redirect('work_shifts.php');