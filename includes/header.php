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
    <a class="navbar-brand" href="index.php">SprintBoard</a>
    <?php if (isAuthenticated()): ?>
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <a class="text-white" href="sprints.php">Esprints</a>
        <a class="text-white" href="teams.php">Equips</a>
        <a class="text-white" href="board.php">Tauler</a>
        <a class="text-white" href="task-create.php">Nova tasca</a>
        <a class="text-white" href="evidences.php">Evidències</a>
        <a class="text-white" href="statistics.php">Estadístiques</a>
        <a class="text-white" href="history.php">Històric</a>
        <a class="text-white" href="users.php">Usuaris</a>
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
