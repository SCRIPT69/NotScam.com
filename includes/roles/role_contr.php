<?php
declare(strict_types=1);

require_once __DIR__ . '/role_model.php';

/**
 * Změní roli uživatele podle jeho id.
 *
 * - Ověří platnost požadované role
 * - Najde uživatele podle id
 * - Zabrání změně vlastní role administrátora
 * - Zabrání změně na stejnou roli
 * - Při chybě uloží validační chyby a data do session
 * - Při úspěchu aktualizuje roli uživatele v databázi
 *
 * @param string $userId id uživatele, jehož role se mění
 * @param string $newRole Nová role ('user' nebo 'admin')
 * @param int $currentAdminId ID aktuálně přihlášeného administrátora
 * @param PDO $pdo Aktivní databázové připojení
 *
 * @return bool True při úspěšné změně role, false při validační chybě
 */
function changeUserRole(string $userId, string $newRole, int $currentAdminId, PDO $pdo): bool {
    $user = getUserById($pdo, $userId);
    $errors = [];

    if (!in_array($newRole, ['user', 'admin'], true)) {
        $errors['newRole'] = 'Neplatná role';
    }
    
    if (!$user) {
        $errors["newRole"] = 'Uživatel nenalezen';
    }
    else if ((int)$user['id'] === $currentAdminId) {
        $errors["newRole"] = 'Nemůžete změnit vlastní roli';
    }
    else if ($user['role'] === $newRole) {
        $errors["newRole"] = 'Uživatel už tuto roli má';
    }

    if (!empty($errors)) {
        $_SESSION["role-errors"] = $errors;
        return false;
    }

    updateUserRole($pdo, (int)$user['id'], $newRole);

    return true;
}