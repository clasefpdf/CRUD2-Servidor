<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$users = getDB()->query('SELECT * FROM users ORDER BY name')->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<h1>Usuaris</h1>
<ul class="list-group mb-4"><?php foreach ($users as $user): ?>
    <li class="list-group-item"><a href="user.php?id=<?= (int) $user['id'] ?>"><?= h($user['name']) ?></a></li>
<?php endforeach; ?></ul>
<a class="btn btn-primary" href="user-create.php">Crear usuari</a>
<?php require __DIR__ . '/../includes/footer.php'; ?>