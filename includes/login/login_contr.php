<?php

declare(strict_types=1);

require_once __DIR__ . '/../validation/validators.php';

/**
 * Hlavní funkce pro zpracování přihlášení uživatele.
 *
 * - Validační kontrola vstupních dat.
 * - Pokus o autentizaci uživatele podle e-mailu a hesla.
 * - Při neúspěchu uloží chyby a vrátí false.
 * - Při úspěchu inicializuje session a vrací true.
 *
 * @param array $data Vstupní hodnoty z login formuláře.
 * @param PDO $pdo PDO instance databáze.
 * @return bool True při úspěšném přihlášení, jinak false.
 */
function loginUser(array $data, PDO $pdo): bool {
    $errors = validateLogin($data);
        
    // Pokud jsou validační chyby - uložit do session a ukončit
    if (!empty($errors)) {
        $_SESSION["login-errors"] = $errors;

        unset($data["password"]);
        $_SESSION["login-data"] = $data;

        return false;
    }
    unset($_SESSION["login-errors"], $_SESSION["login-data"]);

    // Pokus o autentizaci (ověření, že e-mail existuje a heslo sedí)
    $user = authenticateUser($data["email"], $data["password"], $pdo);
    if (!$user) {
        // Neúspěšné přihlášení – chyba kombinace e-mailu a hesla
        $_SESSION["login-errors"] = ["password" => "Zadaný e-mail nebo heslo není správné!"];
        unset($data["password"]);
        $_SESSION["login-data"] = $data;
        return false;
    }

    // Úspěšné ověření - Přihlášení uživatele
    regenerateSessionId();
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_role"] = $user["role"];

    return true;
}

/**
 * Validuje vstupní data přihlašovacího formuláře.
 *
 * @param array $data Asociativní pole obsahující 'email' a 'password'.
 *
 * @return array Pole chyb, kde klíč je název pole a hodnota je text chyby.
 */
function validateLogin(array $data): array {
    $errors = [];

    if ($error = checkEmailFormatForErrors($data["email"])) {
        $errors["email"] = $error;
    }
    if ($error = checkValueSizeErrors($data["password"], 100)) {
        $errors["password"] = $error;
    }

    return $errors;
}