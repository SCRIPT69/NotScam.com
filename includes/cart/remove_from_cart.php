<?php
require_once __DIR__ . '/../session_manager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'user') {
    $id = (int)$_POST['id'];
    unset($_SESSION['cart'][$id]);
}

header("Location: ../../cart.php");
exit;