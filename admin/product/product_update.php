<?php
declare(strict_types=1);
/**
 * Endpoint pro aktualizaci produktu (admin).
 *
 * - Přístup pouze pro administrátora
 * - Zpracovává POST data z formuláře úpravy produktu
 * - Volá updateProduct(), která provede validaci a aktualizaci
 * - Po úspěchu přesměruje do admin panelu
 * - Při chybě vrátí zpět na stránku editace produktu
 */
require_once __DIR__ . '/../../includes/session_manager.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "admin") {
    if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
        header("Location: ../admin_panel.php");
        exit;
    }
    $productId = (int) $_POST['id'];

    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $image = $_FILES["image"];

    $product_data = ["id"=>$productId, "name" => $name, "description" => $description,
                    "price" => $price, "image" => $image
    ];

    try {
        require_once __DIR__ . '/../../includes/dbh.php';
        require_once __DIR__ . '/../../includes/products/product_model.php';
        require_once __DIR__ . '/../../includes/products/product_contr.php';

        if (updateProduct($product_data, $pdo)) {
            header("Location: ../admin_panel.php");
            exit;
        }

        header("Location: ../editProduct.php?id={$productId}");
        exit;
    }
    catch (PDOException $e) {
        exit("Query failed: ".$e->getMessage());
    }
}
else {
    header("Location: ../../index.php");
    exit;
}