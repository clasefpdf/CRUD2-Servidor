<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
if (isAuthenticated()) {
    redirect('index.php');
}
$error = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if ($username === '' || $password === '') {
        $error = 'Tots els camps són obligatoris.';
    } elseif (login($username, $password)) {
        setFlash('success', 'Has iniciat sessió correctament.');
        redirect('index.php');
    } else {
        $error = 'Usuari o contrasenya incorrectes.';
    }
}
require __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center"><div class="col-md-5">
    <h1>Inicia sessió</h1>
    <?php if ($error !== ''): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>
    <form method="post">
        <?= csrfField() ?>
        <label for="username" class="form-label">Usuari</label>
        <input class="form-control mb-3" id="username" name="username" value="<?= h($username) ?>" required>
        <label for="password" class="form-label">Contrasenya</label>
        <input class="form-control mb-3" id="password" name="password" type="password" required>
        <button class="btn btn-primary">Entrar</button>
    </form>
    <p class="text-muted mt-3">Usuaris de prova: admin, alice, bob, carol, david i eva. Contrasenya: 1234.</p>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
