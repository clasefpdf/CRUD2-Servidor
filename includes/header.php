<?php declare(strict_types=1); 
use App\Util\Flash;
?>

<!doctype html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4"><div class="container flex-wrap gap-2">
    <a class="navbar-brand" href="index.php">Equip 2</a>
    <?php if (isAuthenticated()): ?>
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <a class="text-white" href="history.php">Escandall de productes</a>
        <a class="text-white" href="history.php">Ordres de fabricacio</a>
        <a class="text-white" href="history.php">Maquines</a>        
        <a class="text-white" href="users.php">Usuarios</a>
        <?php if (isTeacher()): ?><a class="text-white" href="shifts.php">Turnos</a><?php endif; ?>
        <a class="text-white" href="profile.php"><?= h($_SESSION['user_name']) ?></a>
        <form method="post" action="logout.php" class="m-0"><?= csrfField() ?><button class="btn btn-sm btn-outline-light">Eixir</button></form>
    </div>
    <?php endif; ?>
</div></nav>
<main class="container pb-5">
<?php
$flashes = Flash::get();
require __DIR__ . '/../templates/layout/flashes.php';
?>
