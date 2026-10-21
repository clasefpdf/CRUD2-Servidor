<?php
declare(strict_types=1);

use App\Sprint;
use App\Validation\SprintValidator;

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
if (!isTeacher()) {
    http_response_code(403);
    exit('Acceso denegado.');
}

$errors = [];
$id = positiveId($_GET['id'] ?? null);
$db = getDB();
if ($id === null) {
    http_response_code(404);
    exit('Tasca no trobada.');
}
$stmt = getDB()->prepare('SELECT * FROM sprints WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    http_response_code(404);
    exit('Sprint no trobat.');
}

$values = [
    'name' => (string) $row['name'],
    'description' => (string) $row['goal'],
    'date_start' => (string) substr($row['start_date'], 0, 10),
    'date_end' => (string) substr($row['end_date'], 0, 10),
    'state' => (string) $row['status'], 
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }

    $errors = (new SprintValidator())->validate($values);
    if ($errors === []) {
        $stmt = $db->prepare('SELECT 1 FROM sprints WHERE name = ? AND id <> ?');
        $stmt->execute([$values['name'], $id]);
        if ($stmt->fetch()) {
            $errors[] = 'Ja existeix un altre sprint amb aquest nom.';
        }
    }

    if ($errors === []) {
        $sprint = new Sprint(
            $values['name'],
            $values['description'],
            new \DateTime($values['date_start']),
            new \DateTime($values['date_end']),
            $values['state']
        );

        $stmt = $db->prepare('UPDATE sprints SET name = ?, goal = ?, start_date = ?, end_date = ?, status = ? WHERE id = ?');
        $stmt->execute([
            $sprint->getName(),
            $sprint->getDescription(),
            $sprint->getDateStart()->format('Y-m-d'),
            $sprint->getDateEnd()->format('Y-m-d'),
            $sprint->getState(),
            $id,
        ]);

        setFlash('success', 'Sprint actualitzat correctament.');
        redirect('sprints.php');
    }
}
// TODO (alumnat): carregar les dades actuals, validar el formulari d'edició i guardar els canvis amb SQL preparat i CSRF.
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/edit-sprint.php';
require __DIR__ . '/../includes/footer.php';
