<?php
/**
 * Entry point pro zpracování přihlašovacího formuláře.
 *
 * - Načte vstupní hodnoty z POSTu.
 * - Předá je kontroleru pro validaci a autentizaci.
 * - Podle výsledku provede přesměrování na login nebo homepage.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $login_data = ["email"=>$email, "password"=>$password];

    try {
        require_once __DIR__ . '/../dbh.php';
        require_once __DIR__ . '/../session_manager.php';
        require_once __DIR__ . '/login_model.php';
        require_once __DIR__ . '/login_contr.php';

        if (loginUser($login_data, $pdo)) {
            header("Location: ../../index.php");
            exit;
        }
        else {
            header("Location: ../../login.php");
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