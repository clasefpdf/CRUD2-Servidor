<?php

declare(strict_types=1);
?>
<h1>Nou usuari</h1>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post">
    <?= csrfField() ?>
    <label class="form-label" for="name">Nom *</label>
    <input class="form-control mb-3" id="name" name="name" maxlength="150" value="<?= h($values['name']) ?>" >
    <label class="form-label" for="username">usuari *</label>
    <input class="form-control mb-3" id="username" name="username" maxlength="2000" value="<?= h($values['username']) ?>" >
    <label class="form-label" for="email">email *</label>
    <input class="form-control mb-3" id="email" name="email" type="email" maxlength="255" value="<?= h($values['email']) ?>" >
    <label class="form-label" for="password">contrasenya *</label>
    <input class="form-control mb-3" id="password" name="password" type="password" minlength="4" value="">
    <label class="form-label" for="role">Rol</label>
    <select class="form-select mb-3" id="role" name="role">
        <option value="student" <?= $values['role'] === 'student' ? 'selected' : '' ?>>Alumne</option>
        <option value="teacher" <?= $values['role'] === 'teacher' ? 'selected' : '' ?>>Professor</option>
    </select>
    <button class="btn btn-primary">Crear usuari</button>
</form>
