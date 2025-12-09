<?php
declare(strict_types=1);

/**
 * AJAX endpoint pro kontrolu, zda e-mail již existuje v databázi.
 *
 * Používá se při validaci registračního formuláře na frontendu.
 * 
 * - Přijímá parametr "email" přes GET.
 * - Vrací JSON objekt { exists: true|false }.
 * - Slouží k okamžitému ověření, zda je e-mail již zaregistrován.
 *
 */

header("Content-Type: application/json");

if (!isset($_GET["email"])) {
    echo json_encode(["exists" => false]);
    exit;
}

$email = trim($_GET["email"]);

require_once __DIR__ . '/../dbh.php';
require_once __DIR__ . '/../validation/validators.php';

echo json_encode(["exists" => checkIfEmailAlreadyUsed($email, $pdo)]);
exit;