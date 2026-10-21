<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$stmt = getDB()->prepare('SELECT username, name, email, role FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
require __DIR__ . '/../includes/header.php';
?>
<h1>El meu perfil</h1>
<?php if ($user): ?>
<dl><dt>Nom</dt><dd><?= h($user['name']) ?></dd>
<dt>Usuari</dt><dd><?= h($user['username']) ?></dd>
<dt>Correu</dt><dd><?= h($user['email']) ?></dd>
<dt>Tipus</dt><dd><?= h($user['role']) ?></dd></dl>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
