<?php
declare(strict_types=1);

/**
 * Entry point pro odstranění produktu (admin).
 *
 * - Ověří, že požadavek je POST
 * - Ověří, že uživatel je admin
 * - Ověří platnost ID produktu
 * - Smaže produkt včetně obrázku
 * - Přesměruje zpět do admin panelu
 */

require_once __DIR__ . '/../../includes/session_manager.php';
require_once __DIR__ . '/../../includes/dbh.php';
require_once __DIR__ . '/../../includes/products/product_model.php';

// Povolen pouze POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php");
    exit;
}

// Pouze admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

// Kontrola ID produktu
if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    header("Location: ../admin_panel.php");
    exit;
}

$productId = (int)$_POST['id'];

try {
    deleteProductById($pdo, $productId);
}
catch (PDOException $e) {
    exit("Chyba databáze");
}

// Zpět do admin panelu
header("Location: ../admin_panel.php");
exit;