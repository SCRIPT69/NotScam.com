<?php

declare(strict_types=1);

require_once __DIR__ . '/../validation/validators.php';

/**
 * Controller pro vytváření produktů.
 * 
 * - Provede validaci vstupních dat (název, popis, cena, obrázek).
 * - Při chybě uloží validační chyby a data do session.
 * - Vloží produkt do databáze bez obrázku a získá jeho ID.
 * - Pokud je nahrán obrázek, uloží ho pod názvem "<productId>.<ext>".
 * - Aktualizuje cestu k obrázku v databázi.
 * - Při selhání ukládání obrázku odstraní právě vytvořený produkt.
 *
 * @param array $data  Data produktu z formuláře (včetně $_FILES).
 * @param PDO   $pdo   Aktivní databázové připojení.
 * @param int   $userId ID administrátora, který produkt vytváří.
 *
 * @return bool True při úspěšném vytvoření produktu, false při chybě.
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
 * Aktualizuje existující produkt.
 *
 * - Provede validaci vstupních dat
 * - Při chybě uloží validační zprávy do session
 * - Aktualizuje základní údaje produktu (název, popis, cena)
 * - Pokud je nahrán nový obrázek, nahradí původní
 *
 * @param array $data Data produktu z formuláře (včetně ID a případně obrázku)
 * @param PDO $pdo Aktivní databázové připojení
 *
 * @return bool True při úspěchu, false při chybě validace
 */
function updateProduct(array $data, PDO $pdo): bool
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

    updateProductBaseInfo(
        $pdo,
        $data['id'],
        $data['name'],
        $data['description'],
        (float)$data['price']
    );

    if (isset($data['image']) && $data['image']['error'] === UPLOAD_ERR_OK) {
        replaceProductImage($pdo, $data['id'], $data['image']);
    }

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

/**
 * Načte produkt pro stránku detailu produktu.
 *
 * - Ověří existenci produktu podle ID.
 * - Pokud produkt neexistuje, provede přesměrování (např. na hlavní stránku).
 * - Vrací data produktu připravená pro zobrazení ve view.
 *
 * @param PDO $pdo Připojení k databázi.
 * @param int $productId ID produktu z URL.
 *
 * @return array Data produktu pro šablonu.
 */
function loadProduct(PDO $pdo, int $id): array
{
    $product = getProductById($pdo, $id);

    if (!$product) {
        header("Location: index.php");
        exit;
    }

    return $product;
}