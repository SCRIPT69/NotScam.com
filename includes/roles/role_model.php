<?php
declare(strict_types=1);

/**
 * Načte seznam všech uživatelů (pro admin panel).
 *
 * @param PDO $pdo
 * @return array
 */
function getAllUsers(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT id, email, name, role
        FROM users
        ORDER BY created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Načte uživatele podle id
 *
 * @param PDO $pdo Aktivní databázové připojení
 * @param string $id
 *
 * @return array|null Asociativní pole s daty uživatele nebo null
 */
function getUserById(PDO $pdo, string $id): ?array
{
    $stmt = $pdo->prepare("
        SELECT id, name, email, role
        FROM users
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);

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