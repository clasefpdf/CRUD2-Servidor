<?php
// Creacio de sprint
declare(strict_types=1);
use App\Sprint;
use App\Validation\SprintValidator;

require __DIR__ . '/../includes/bootstrap.php';

requireAuth();
if (!isTeacher()) {
    http_response_code(403);
    exit('Acceso denegado.');
}
$db = getDB();
$values = ['name' => '' , 'description' => '', 'date_start' => '', 'date_end' => '', 'state' => ''];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new SprintValidator())->validate($values);
    if ($errors === []) {
        $stmt = $db->prepare('SELECT 1 FROM sprints WHERE name = ?');
        $stmt->execute([$values['name']]);
        if ($stmt->fetch()) {
            $errors[] = 'El sprint ja existeix.';
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
    $stmt = $db->prepare('INSERT INTO sprints (name, goal, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $sprint->getName(),
        $sprint->getDescription(),
        $sprint->getDateStart()->format('Y-m-d H:i:s'),
        $sprint->getDateEnd()->format('Y-m-d H:i:s'),
        $sprint->getState(),
    ]);
    setFlash('success', 'Sprint creat correctament.');
    redirect('sprints.php');
}
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/createSprints.php';
require __DIR__ . '/../includes/footer.php';
