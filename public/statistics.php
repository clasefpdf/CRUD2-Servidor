<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
requireAuth();
$sprint = getDB()->query("SELECT name FROM sprints WHERE status = 'active' LIMIT 1")->fetch();
// TODO (alumnat): calcular tasques per estat, tasques completades per equip, participació per alumne i percentatge de finalització.
require __DIR__ . '/../includes/header.php';
?>
<h1>Estadístiques</h1>
<p>Esprint actual: <?= h($sprint['name'] ?? 'Cap esprint actiu') ?></p>
<p class="text-muted">Les mètriques estan pendents de calcular.</p>
<?php require __DIR__ . '/../includes/footer.php'; ?>
