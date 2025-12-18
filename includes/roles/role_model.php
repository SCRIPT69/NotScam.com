<?php
declare(strict_types=1);

/**
 * Načte uživatele podle e-mailu.
 *
 * @param PDO $pdo Aktivní databázové připojení
 * @param string $email
 *
 * @return array|null Asociativní pole s daty uživatele nebo null
 */
function getUserByEmail(PDO $pdo, string $email): ?array
{
    $stmt = $pdo->prepare("
        SELECT id, role
        FROM users
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

/**
 * Aktualizuje roli uživatele podle ID.
 *
 * @param PDO $pdo
 * @param int $userId
 * @param string $role
 *
 * @return void
 */
function updateUserRole(PDO $pdo, int $userId, string $role): void
{
    $stmt = $pdo->prepare("
        UPDATE users
        SET role = :role
        WHERE id = :id
    ");
    $stmt->execute([
        ':role' => $role,
        ':id'   => $userId
    ]);
}