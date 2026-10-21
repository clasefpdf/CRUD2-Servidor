<?php

declare(strict_types=1);

function login(string $username, string $password): bool
{
    $stmt = getDB()->prepare('SELECT id, username, name, role, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    return true;
}

function isAuthenticated(): bool
{
    return isset($_SESSION['user_id']);
}

function isTeacher(): bool
{
    return ($_SESSION['user_role'] ?? null) === 'teacher';
}

function requireAuth(): void
{
    if (!isAuthenticated()) {
        setFlash('error', 'Cal iniciar sessió per accedir a esta pàgina.');
        redirect('login.php');
    }
}
