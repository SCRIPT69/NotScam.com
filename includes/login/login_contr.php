<?php

declare(strict_types=1);

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
function returnErrorIfEmpty(string $value): ?string {
    if (trim($value) == "") {
        return "Vyplňte pole!";
    }
    return null;
}

/**
 * Vrací chybu, pokud je value příliš dlouhé.
 *
 * @param string $value Hodnota pole.
 * @param int $maxLength Maximální délka hodnoty.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function returnErrorIfReachedLengthLimit(string $value, int $maxLength): ?string {
    if (mb_strlen($value) > $maxLength) {
        return "Překročena maximální délka!";
    }
    return null;
}

/**
 * Validuje e-mail u přihlášení.
 *
 * @param string $email E-mail uživatele.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getEmailError(string $email): ?string {
    if ($error = returnErrorIfEmpty($email)) return $error;
    if ($error = returnErrorIfReachedLengthLimit($email, 100)) return $error;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Zadejte platný e-mail!";
    }
    return null;
}

/**
 * Validuje heslo u přihlášení.
 *
 * @param string $password Heslo z formuláře.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getPasswordError(string $password): ?string {
    if ($error = returnErrorIfReachedLengthLimit($password, 100)) return $error;
    return returnErrorIfEmpty($password);
}