<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . '/../session_manager.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    echo json_encode(["success" => false, "error" => "Nepřihlášen"]);
    exit;
}

$payload = json_decode(file_get_contents("php://input"), true);

if (!isset($payload['productId']) || !ctype_digit((string)$payload['productId'])) {
    echo json_encode(["success" => false, "error" => "Neplatný produkt"]);
    exit;
}

$productId = (int)$payload['productId'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = true;
}

echo json_encode(["success" => true]);