<?php

declare(strict_types=1);

function h(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url, true, 303);
    exit;
}

function positiveId(mixed $value): ?int
{
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}

function statusBadge(string $status): string
{
    return match ($status) {
        'active' => '<span class="badge bg-success">Actiu</span>',
        'completed' => '<span class="badge bg-secondary">Finalitzat</span>',
        'pending' => '<span class="badge bg-warning text-dark">Pendent</span>',
        'todo' => '<span class="badge bg-secondary">To do</span>',
        'in_progress' => '<span class="badge bg-primary">In progress</span>',
        'done' => '<span class="badge bg-success">Done</span>',
        default => h($status),
    };
}
