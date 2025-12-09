<?php

declare(strict_types=1);

require_once __DIR__ . '/../validation/validators.php';

/**
 * Zpracuje registraci uživatele jako kontroler.
 *
 * - Spustí validační logiku.
 * - Při chybách uloží chybové zprávy i původní data do session.
 * - Při úspěchu uloží uživatele do DB, vytvoří session a přihlásí ho.
 *
 * @param array $data Data z registračního formuláře.
 * @param PDO $pdo DB připojení.
 *
 * @return bool True při úspěšné registraci, false při chybách.
 */
function registerUser(array $data, PDO $pdo): bool {
    $errors = validateRegister($data, $pdo);

    if (!empty($errors)) {
        $_SESSION["register-errors"] = $errors;

        unset($data["password"]);
        unset($data["passwordconfirm"]);
        $_SESSION["register-data"] = $data;

        return false;
    }
    unset($_SESSION["register-errors"], $_SESSION["register-data"]);

    saveNewUserToDB($pdo, $data["name"], $data["email"], $data["password"]);

    $userId = (int)$pdo->lastInsertId();
    regenerateSessionId();
    $_SESSION["user_id"] = $userId;
    $_SESSION["user_role"] = "user";

    return true;
}

/**
 * Provede kompletní validaci registračních dat.
 *
 * @param array $data Asociativní pole obsahující vstupní hodnoty formuláře.
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return array Pole chyb, kde klíč je název pole a hodnota je text chyby.
 */
function validateRegister(array $data, PDO $pdo): array {
    $errors = [];

    if ($error = checkNameForErrors($data["name"])) {
        $errors["name"] = $error;
    }
    if ($error = checkEmailRegisterForErrors($data["email"], $pdo)) {
        $errors["email"] = $error;
    }
    if ($error = checkPasswordForErrors($data["password"])) {
        $errors["password"] = $error;
    }
    if ($error = checkPasswordConfirmForErrors($data["passwordconfirm"], $data["password"])) {
        $errors["passwordconfirm"] = $error;
    }
    if ($error = checkCardNumberForErrors($data["cardnumber"])) {
        $errors["cardnumber"] = $error;
    }
    if ($error = checkCardExpirationForErrors($data["cardexpiration"])) {
        $errors["cardexpiration"] = $error;
    }
    if ($error = checkCardCVVForErrors($data["cardcvv"])) {
        $errors["cardcvv"] = $error;
    }
    if ($error = checkCheckboxAgreeForErrors($data["checkboxagree"])) {
        $errors["checkboxagree"] = $error;
    }

    return $errors;
}


/**
 * Validuje e-mail a ujistí se, že ještě nebyl použit
 *
 * @param string $email E-mailová adresa.
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkEmailRegisterForErrors(string $email, PDO $pdo): ?string {
    if ($error = checkEmailFormatForErrors($email)) return $error;
    if ($error = checkIfEmailAlreadyUsed($email, $pdo)) return $error;

    return null;
}