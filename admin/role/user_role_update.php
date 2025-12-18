<?php
declare(strict_types=1);
/**
 * Entry point pro změnu role uživatele
 *
 * - Přístupný pouze administrátorovi.
 * - Načte e-mail cílového uživatele a novou roli z POST dat.
 * - Zavolá controller, který provede validaci a změnu role.
 * - Při úspěchu přesměruje zpět do admin panelu se success parametrem.
 * - Při chybě uloží validační chyby do session a vrátí zpět do admin panelu.
 */
require_once __DIR__ . '/../../includes/session_manager.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "admin") {
    $email = trim($_POST['email'] ?? '');
    $newRole = $_POST['newRole'] ?? '';

    try {
        require_once __DIR__ . '/../../includes/dbh.php';
        require_once __DIR__ . '/../../includes/roles/role_model.php';
        require_once __DIR__ . '/../../includes/roles/role_contr.php';

        if (changeUserRole($email, $newRole, (int)$_SESSION['user_id'], $pdo)) {
            header('Location: ../admin_panel.php?role_success=1');
            exit;
        }

        header('Location: ../admin_panel.php');
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