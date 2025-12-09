<?php
declare(strict_types=1);

/**
 * API endpoint pro smazání uživatelského účtu přes AJAX.
 *
 * - Přijímá JSON payload s požadavkem na smazání účtu.
 * - Ověří, zda je uživatel přihlášen.
 * - Po úspěšném smazání provede odhlášení bez redirectu.
 * - Vrací JSON odpověď pro front-end (success / error).
 */

header("Content-Type: application/json");

require_once __DIR__ . '/../session_manager.php';
require_once __DIR__ . '/../dbh.php';
require_once __DIR__ . '/profile_model.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "error" => "Nejste přihlášen!"]);
    exit;
}

$payload = json_decode(file_get_contents("php://input"), true);

if (!$payload || !isset($payload["delete"])) {
    echo json_encode(["success" => false, "error" => "Neplatný požadavek!"]);
    exit;
}

try {
    deleteUserById((string)$_SESSION["user_id"], $pdo);

    // Logout bez redirect
    require_once __DIR__ . '/../logout_logic.php';

    echo json_encode(["success" => true]);
}
catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Chyba databáze!"]);
}