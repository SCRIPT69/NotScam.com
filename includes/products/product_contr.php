<?php

declare(strict_types=1);

require_once __DIR__ . '/../validation/validators.php';

/**
 * Controller pro vytváření produktů.
 *
 * - Validuje vstupní data.
 * - Vloží produkt do DB bez obrázku.
 * - Uloží obrázek pod názvem ID (např. "15.jpg").
 * - Aktualizuje image_path v DB.
 * - Vrací true/false podle úspěchu.
 */
function createProduct(array $data, PDO $pdo, int $userId): bool
{
    // Validace dat
    $errors = validateProduct($data);
    if (!empty($errors)) {
        $_SESSION["product-errors"] = $errors;
        $_SESSION["product-data"] = [
            "name" => $data["name"],
            "description" => $data["description"],
            "price" => $data["price"],
        ];
        return false;
    }

    unset($_SESSION["product-errors"], $_SESSION["product-data"]);

    // Vložíme produkt bez obrázku, získáme ID
    $productId = insertProductBaseInfo(
        $pdo,
        $data["name"],
        $data["description"],
        (float)$data["price"],
        $userId
    );

    if (!$productId) {
        return false;
    }

    // Pokud nebyl nahrán žádný obrázek → hotovo
    if (!isset($data["image"]) || $data["image"]["error"] !== UPLOAD_ERR_OK) {
        return true;
    }

    // Uložíme obrázek pod názvem: "<id>.ext"
    $savedImageName = saveProductImageById($data["image"], $productId);

    if (!$savedImageName) {
        // pokud selže obrázek, smažeme produkt
        deleteProductById($pdo, $productId);
        $_SESSION["product-errors"] = ["image" => "Nepodařilo se uložit obrázek!"];
        return false;
    }

    // UPDATE image_path
    updateProductImagePath($pdo, $productId, $savedImageName);

    return true;
}

/**
 * Validuje vstupní data produktu (název, popis, cenu a případně obrázek).
 *
 * @param array $data Data z formuláře.
 * @return array Pole chyb; prázdné při úspěšné validaci.
 */
function validateProduct(array $data): array
{
    $errors = [];

    if ($error = checkValueSizeErrors($data["name"], 255)) {
        $errors["name"] = $error;
    }
    if ($error = checkValueSizeErrors($data["description"], 1000)) {
        $errors["description"] = $error;
    }
    if ($error = checkPriceForErrors($data["price"])) {
        $errors["price"] = $error;
    }
    if ($error = checkProductImageForErrors($data["image"])) {
        $errors["image"] = $error;
    }

    return $errors;
}