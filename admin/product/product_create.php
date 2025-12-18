<?php
declare(strict_types=1);
/**
 * Entry point pro zpracování POST formuláře pro vytvoření produktu.
 *
 * - Načte vstupní data.
 * - Zavolá controller pro validaci a uložení produktu.
 * - Podle výsledku provede přesměrování.
 */
require_once __DIR__ . '/../../includes/session_manager.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "admin") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $image = $_FILES["image"];

    $product_data = [ "name" => $name, "description" => $description,
                    "price" => $price, "image" => $image
    ];

    try {
        require_once __DIR__ . '/../../includes/dbh.php';
        require_once __DIR__ . '/../../includes/products/product_model.php';
        require_once __DIR__ . '/../../includes/products/product_contr.php';

        if (createProduct($product_data, $pdo, (int)$_SESSION["user_id"])) {
            header("Location: ../admin_panel.php?success=1");
            exit;
        } else {
            header("Location: ../admin_panel.php?error=1");
            exit;
        }
    }
    catch (PDOException $e) {
        exit("Query failed: ".$e->getMessage());
    }
}
else {
    header("Location: ../../index.php");
    exit;
}