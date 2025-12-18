<?php

/**
 * Načte produkty pro konkrétní stránku (pagination).
 *
 * Vrací omezený počet produktů z databáze podle limitu a offsetu,
 * seřazené podle data vytvoření.
 *
 * @param PDO $pdo Databázové připojení.
 * @param int $limit Počet produktů na stránku.
 * @param int $offset Posun v databázi.
 *
 * @return array Seznam produktů.
 */
function getProductsPaginated(PDO $pdo, int $limit, int $offset, string $sort): array
{
    $order = $sort === 'old'
        ? 'created_at ASC'
        : 'created_at DESC';

    $stmt = $pdo->prepare("
        SELECT *
        FROM products
        ORDER BY $order
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Načte jeden produkt z databáze podle jeho ID.
 *
 * Vrací kompletní informace o produktu (název, popis, cena, obrázek, datum vytvoření),
 * nebo null, pokud produkt s daným ID neexistuje.
 *
 * @param PDO $pdo Připojení k databázi.
 * @param int $productId ID produktu.
 *
 * @return array|null Asociativní pole s daty produktu nebo null.
 */
function getProductById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("
        SELECT id, name, description, price, image_path
        FROM products
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    return $product ?: null;
}

/**
 * Vrátí produkty podle pole ID (pro košík).
 *
 * @param PDO   $pdo
 * @param int[] $ids
 *
 * @return array
 */
function getProductsByIds(PDO $pdo, array $ids): array
{
    if (empty($ids)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("
        SELECT id, name, price, image_path
        FROM products
        WHERE id IN ($placeholders)
    ");

    $stmt->execute($ids);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Načte všechny produkty z databáze.
 *
 * @param PDO $pdo Aktivní databázové připojení.
 *
 * @return array Seznam všech produktů jako asociativní pole.
 */
function getAllProducts(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT id, name, price, image_path
        FROM products
        ORDER BY created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Vrátí celkový počet produktů v databázi.
 *
 * Používá se pro výpočet stránkování.
 *
 * @param PDO $pdo Databázové připojení.
 *
 * @return int Počet produktů.
 */
function getProductsCount(PDO $pdo): int
{
    return (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
}

/**
 * Vloží nový produkt do databáze bez obrázku a vrátí jeho ID.
 *
 * @param PDO    $pdo         Aktivní PDO připojení k databázi.
 * @param string $name        Název produktu.
 * @param string $description Popis produktu.
 * @param float  $price       Cena produktu.
 * @param int    $userId      ID administrátora, který produkt vytvořil.
 *
 * @return int|null ID nově vytvořeného produktu, nebo null při neúspěchu.
 */
function insertProductBaseInfo(PDO $pdo, string $name, string $description, float $price, int $userId): ?int
{
    $stmt = $pdo->prepare("
        INSERT INTO products (name, description, price, created_by)
        VALUES (:name, :description, :price, :userId)
    ");

    $stmt->execute([
        ":name" => $name,
        ":description" => $description,
        ":price" => $price,
        ":userId" => $userId
    ]);

    return (int)$pdo->lastInsertId();
}

/**
 * Aktualizuje základní údaje produktu v databázi.
 *
 * Upravuje název, popis a cenu produktu podle jeho ID.
 * Neřeší obrázek produktu.
 *
 * @param PDO    $pdo         Aktivní PDO instance.
 * @param int    $productId  ID produktu.
 * @param string $name        Nový název produktu.
 * @param string $description Nový popis produktu.
 * @param float  $price       Nová cena produktu.
 *
 * @return void
 */
function updateProductBaseInfo(
    PDO $pdo,
    int $productId,
    string $name,
    string $description,
    float $price
): void {
    $stmt = $pdo->prepare("
        UPDATE products
        SET name = :name,
            description = :description,
            price = :price
        WHERE id = :id
    ");

    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':id' => $productId
    ]);
}

/**
 * Nahradí obrázek produktu novým.
 *
 * - Načte aktuální produkt z DB
 * - Pokud existuje starý obrázek, smaže ho
 * - Uloží nový obrázek pod názvem "<productId>.<ext>"
 * - Aktualizuje image_path v databázi
 *
 * @param PDO   $pdo
 * @param int   $productId
 * @param array $file  Nahraný soubor z $_FILES
 *
 * @return bool True při úspěchu, false při chybě
 */
function replaceProductImage(PDO $pdo, int $productId, array $file): bool
{
    $product = getProductById($pdo, $productId);

    if (!$product) {
        return false;
    }

    // Smazání starého obrázku, pokud existuje
    if (!empty($product['image_path'])) {
        deleteProductImageFile($product['image_path']);
    }

    // Uložení nového obrázku
    $newImageName = saveProductImageById($file, $productId);

    if ($newImageName === false) {
        return false;
    }

    // Aktualizace cesty v DB
    updateProductImagePath($pdo, $productId, $newImageName);

    return true;
}

/**
 * Uloží nahraný obrázek produktu pod názvem založeným na ID produktu.
 *
 * Název souboru má formát: "<productId>.<přípona>".
 * Funkce předpokládá, že nahraný soubor již prošel validací.
 *
 * @param array $file      Soubor nahraný přes $_FILES.
 * @param int   $productId ID produktu, ke kterému obrázek patří.
 *
 * @return string|false Název uloženého souboru při úspěchu, nebo false při chybě.
 */
function saveProductImageById(array $file, int $productId): false|string
{
    $uploadDir = __DIR__ . '/../../uploads/products/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $newName = $productId . "." . $ext;
    $path = $uploadDir . $newName;

    if (!move_uploaded_file($file["tmp_name"], $path)) {
        return false;
    }

    return $newName;
}

/**
 * Smaže soubor obrázku produktu ze souborového systému.
 *
 * Funkce bezpečně ověří existenci souboru v adresáři uploads/products
 * a pokud soubor existuje, odstraní ho pomocí unlink().
 *
 * Používá se při:
 * - mazání produktu
 * - nahrazení obrázku novým
 *
 * @param string $imagePath Název souboru obrázku uložený v databázi.
 *
 * @return void
 */
function deleteProductImageFile(string $imagePath): void
{
    $fullPath = __DIR__ . '/../../uploads/products/' . $imagePath;

    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}

/**
 * Aktualizuje cestu k obrázku v databázi pro daný produkt.
 *
 * @param PDO $pdo Aktivní PDO instance.
 * @param int $id ID produktu.
 * @param string $imageName Název uloženého souboru s obrázkem.
 *
 * @return void
 */
function updateProductImagePath(PDO $pdo, int $id, string $imageName): void
{
    $stmt = $pdo->prepare("UPDATE products SET image_path = :img WHERE id = :id");
    $stmt->execute([":img" => $imageName, ":id" => $id]);
}

/**
 * Odstraní produkt z databáze včetně jeho obrázku (pokud existuje).
 *
 * - Načte cestu k obrázku z DB
 * - Smaže soubor z uploads/products, pokud existuje
 * - Odstraní záznam produktu z databáze
 *
 * @param PDO $pdo       Aktivní PDO instance.
 * @param int $productId ID produktu, který má být smazán.
 *
 * @return void
 */
function deleteProductById(PDO $pdo, int $productId): void
{
    // Zjistíme, zda má produkt obrázek
    $stmt = $pdo->prepare("
        SELECT image_path
        FROM products
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute([":id" => $productId]);
    $imagePath = $stmt->fetchColumn();

    // Pokud existuje obrázek, smažeme soubor
    if ($imagePath) {
        deleteProductImageFile($imagePath);
    }

    // Smažeme produkt z databáze
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([":id" => $productId]);
}