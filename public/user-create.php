<?php
// Creacio d'usuari
declare(strict_types=1);
use App\User;
use App\Validation\UserValidator;

require __DIR__ . '/../includes/bootstrap.php';

requireAuth();
if (!isTeacher()) {
    http_response_code(403);
    exit('Acceso denegado.');
}
requireAuth();
$db = getDB();
$values = ['name' => '', 'username' => '', 'email' => '', 'password' => '', 'role' => 'student'];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    foreach ($values as $key => $_) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $errors = (new UserValidator())->validate($values);
    if ($errors === []) {
        $stmt = $db->prepare('SELECT 1 FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$values['username'], $values['email']]);
        if ($stmt->fetch()) {
            $errors[] = 'L\'usuari ja existeix.';
        }
    }
       if ($errors === []) {
    $user = User::register(
        $values['name'],
        $values['username'],
        $values['email'],
        $values['password'],
        $values['role'],
    );
    try {
        $stmt = $db->prepare('INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $user->getName(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getRole(),
    ]);
    } catch (Throwable $exception) {
        applicationLogger()->error('Error al crear un usuari: No s’ha pogut inicialitzar la base de dades.', ['exception' => $exception]);
            http_response_code(500);
            exit('Error intern al crear un usuari. Torna-ho a provar més tard.');
    }

    setFlash('success', 'Usuari creat correctament.');
    redirect('index.php');
}
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../templates/tasks/createUsers.php';
require __DIR__ . '/../includes/footer.php';
