<?php
declare(strict_types=1);
/**
 * Zpracuje odeslaný registrační formulář.
 *
 * - Načte vstupní data.
 * - Zavolá validační funkce.
 * - Při chybách uloží data i chyby do session.
 * - Při úspěchu pokračuje v dalším zpracování (DB, registrace uživatele, atd.)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordConfirm = $_POST["passwordconfirm"];
    $cardNumber = $_POST["cardnumber"];
    $cardExpiration = $_POST["cardexpiration"];
    $cardCVV = $_POST["cardcvv"];
    $checkboxAgree = isset($_POST["checkboxagree"]);

    $register_data = ["name"=>$name, "email"=>$email, "password"=>$password,
        "passwordconfirm"=>$passwordConfirm, "cardnumber"=>$cardNumber,
        "cardexpiration"=>$cardExpiration, "cardcvv"=>$cardCVV,
        "checkboxagree"=>$checkboxAgree
    ];

    try {
        require_once __DIR__ . '/../dbh.php';
        require_once __DIR__ . '/../session_manager.php';
        require_once 'register_model.php';
        require_once 'register_contr.php';

        $errors = validateRegister($register_data, $pdo);

        if ($errors) {
            $_SESSION["register-errors"] = $errors;

            unset($register_data["password"]);
            unset($register_data["passwordconfirm"]);
            $_SESSION["register-data"] = $register_data;

            header("Location: ../../register.php");
            die();
        }
        unset($_SESSION["register-errors"], $_SESSION["register-data"]);

        saveNewUserToDB($pdo, $name, $email, $password);

        $userId = (int)$pdo->lastInsertId();
        regenerateSessionId();
        $_SESSION["user_id"] = $userId;
        $_SESSION["user_role"] = "user";

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