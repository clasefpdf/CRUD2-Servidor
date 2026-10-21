<?php foreach ($flashes as $flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?>">
        <?= h($flash['message']) ?>
    </div>
<?php endforeach; ?>