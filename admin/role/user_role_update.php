<?php
declare(strict_types=1);
/**
 * Entry point pro změnu role uživatele
 *
 * - Přístupný pouze administrátorovi.
 * - Načte id cílového uživatele a novou roli z POST dat.
 * - Zavolá controller, který provede validaci a změnu role.
 * - Při úspěchu přesměruje zpět do user_detail se success parametrem.
 * - Při chybě uloží validační chyby do session a vrátí zpět do user_detail.
 */
require_once __DIR__ . '/../../includes/session_manager.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "admin") {
    $userId = $_POST['userId'] ?? '';
    $newRole = $_POST['newRole'] ?? '';

    try {
        require_once __DIR__ . '/../../includes/dbh.php';
        require_once __DIR__ . '/../../includes/roles/role_model.php';
        require_once __DIR__ . '/../../includes/roles/role_contr.php';

        if (changeUserRole($userId, $newRole, (int)$_SESSION['user_id'], $pdo)) {
            header("Location: ../user_detail.php?id={$userId}&role_success=1");
            exit;
        }

        header("Location: ../user_detail.php?id={$userId}");
        exit();
    }
    catch (PDOException $e) {
        exit("Query failed: ".$e->getMessage());
    }
}
else {
    header("Location: ../../index.php");
    exit;
}