<?php
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
        //require_once '../includes/dbh.inc.php';
        require_once '../session_manager.php';
        require_once 'login_contr.php';

        $errors = validateLogin($login_data);
        
        if ($errors) {
            $_SESSION["login-errors"] = $errors;

            unset($login_data["password"]);
            $_SESSION["login-data"] = $login_data;

            header("Location: ../../login.php");
            die();
        }
    }
    catch (PDOException $e) {
        die("Query failed: ".$e->getMessage());
    }
}
else {
    header("Location: ../../index.php");
    die();
}