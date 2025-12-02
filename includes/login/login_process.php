<?php
declare(strict_types=1);
/**
 * Zpracuje odeslaný přihlašovací formulář.
 *
 * - Načte vstupní data.
 * - Zavolá validační funkce.
 * - Při chybách uloží data i chyby do session.
 * - Při úspěchu pokračuje v dalším zpracování (ověření uživatele, přihlášení, atd.)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $login_data = ["email"=>$email, "password"=>$password];

    try {
        require_once __DIR__ . '/../dbh.php';
        require_once __DIR__ . '/../session_manager.php';
        require_once 'login_model.php';
        require_once 'login_contr.php';

        $errors = validateLogin($login_data);
        
        if ($errors) {
            $_SESSION["login-errors"] = $errors;

            unset($login_data["password"]);
            $_SESSION["login-data"] = $login_data;

            header("Location: ../../login.php");
            die();
        }
        unset($_SESSION["login-errors"], $_SESSION["login-data"]);

        $user = authenticateUser($pdo, $email, $password);
        if (!$user) {
            $_SESSION["login-errors"] = ["password" => "Zadaný e-mail nebo heslo není správné!"];
            unset($login_data["password"]);
            $_SESSION["login-data"] = $login_data;
            header("Location: ../../login.php");
            die();
        }
        regenerateSessionId();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_role"] = $user["role"];

        header("Location: ../../index.php");
        $pdo = null;
        die();
    }
    catch (PDOException $e) {
        die("Query failed: ".$e->getMessage());
    }
}
else {
    header("Location: ../../index.php");
    die();
}