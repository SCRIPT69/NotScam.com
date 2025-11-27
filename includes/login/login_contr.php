<?php

/**
 * Validuje vstupní data přihlašovacího formuláře.
 *
 * @param array $data Asociativní pole obsahující 'email' a 'password'.
 *
 * @return array Pole chyb, kde klíč je název pole a hodnota je text chyby.
 */
function validateLogin(array $data): array {
    $errors = [];

    if ($error = getEmailError($data["email"])) {
        $errors["email"] = $error;
    }
    if ($error = getPasswordError($data["password"])) {
        $errors["password"] = $error;
    }

    return $errors;
}

/**
 * Vrací chybu, pokud je hodnota prázdná.
 *
 * @param string $value Hodnota pole.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function returnErrorIfEmpty(string $value) {
    if ($value == "") {
        return "Vyplňte pole!";
    }
}

/**
 * Validuje e-mail u přihlášení.
 *
 * @param string $email E-mail uživatele.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getEmailError(string $email) {
    if ($error = returnErrorIfEmpty($email)) return $error;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Zadejte platný e-mail!";
    }
}

/**
 * Validuje heslo u přihlášení.
 *
 * @param string $password Heslo z formuláře.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getPasswordError(string $password) {
    return returnErrorIfEmpty($password);
}