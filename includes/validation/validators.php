<?php

declare(strict_types=1);

require_once 'validators_model.php';

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
 * Validuje jméno uživatele.
 *
 * @param string $name Jméno z formuláře.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkNameForErrors(string $name): ?string {
    if ($error = returnErrorIfEmpty($name)) return $error;
    if ($error = returnErrorIfReachedLengthLimit($name, 40)) return $error;

    if (!preg_match('/^[A-Za-zÀ-ž]+(?: [A-Za-zÀ-ž]+)*$/u', $name) || mb_strlen(trim($name)) < 2) {
        return "Zadejte své pravé jméno!";
    }
    return null;
}

/**
 * Validuje e-mail.
 *
 * @param string $email E-mailová adresa.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkEmailFormatForErrors(string $email): ?string {
    if ($error = returnErrorIfEmpty($email)) return $error;
    if ($error = returnErrorIfReachedLengthLimit($email, 100)) return $error;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Zadejte platný e-mail!";
    }
    return null;
}

/**
 * Ujistí se, že e-mail ještě nebyl použit
 *
 * @param string $email E-mailová adresa.
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkIfEmailAlreadyUsed(string $email, PDO $pdo): ?string {
    if (checkIfEmailExistsInDB($email, $pdo)) {
        return "E-mail se již používá!";
    }
    return null;
}

/**
 * Validuje heslo podle délky a složitosti.
 *
 * @param string $password Heslo, které uživatel zadal.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkPasswordForErrors(string $password): ?string {
    if ($error = returnErrorIfEmpty($password)) return $error;
    if ($error = returnErrorIfReachedLengthLimit($password, 100)) return $error;

    if (mb_strlen($password) < 9) {
        return "Heslo musí mít alespoň 9 znaků!";
    }
    else if (
        !preg_match('/[a-z]/', $password) ||       // neobsahuje malé písmeno
        !preg_match('/[A-Z]/', $password) ||       // neobsahuje velké písmeno
        !preg_match('/\d/', $password) ||          // neobsahuje číslici
        !preg_match('/[^A-Za-z0-9]/', $password)   // neobsahuje speciální znak
    ) {
        return "Heslo musí obsahovat malé písmeno, velké písmeno, a číslici a specialní znak!";
    }
    return null;
}

/**
 * Ověřuje, zda se hesla shodují.
 *
 * @param string $passwordConfirm Potvrzené heslo.
 * @param string $password Původní heslo.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkPasswordConfirmForErrors(string $passwordConfirm, string $password): ?string {
    if ($error = returnErrorIfEmpty($passwordConfirm)) return $error;
    if ($error = returnErrorIfReachedLengthLimit($passwordConfirm, 100)) return $error;

    if ($passwordConfirm != $password) {
        return "Hesla se neshodují!";
    }
    return null;
}

/**
 * Validuje číslo platební karty.
 *
 * @param string $cardNumber Číslo karty (může obsahovat mezery).
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkCardNumberForErrors(string $cardNumber): ?string {
    if ($error = returnErrorIfEmpty($cardNumber)) return $error;
    if ($error = returnErrorIfReachedLengthLimit($cardNumber, 19)) return $error;

    // musí mít 16 znaků,
    // musí obsahovat pouze čísla
    $value = preg_replace('/\s+/', '', $cardNumber); // odstraníme všechny mezery
    if (mb_strlen($value) != 16 || !preg_match('/^\d+$/', $value)) {
        return "Zadejte platné číslo karty!";
    }
    return null;
}

/**
 * Validuje expiraci platební karty.
 *
 * @param string $cardExpiration Expirace ve formátu MM/YY.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkCardExpirationForErrors(string $cardExpiration): ?string {
    if ($error = returnErrorIfEmpty($cardExpiration)) return $error;
    if ($error = returnErrorIfReachedLengthLimit($cardExpiration, 5)) return $error;

    $regex = '/^(\d{1,2})\/(\d{2})$/'; // formát MM/YY

    if (!preg_match($regex, $cardExpiration, $match)) { // nesplňuje formát
        return "Zadejte správnou platnost karty!";
    }

    $month = (int)$match[1]; // získáme měsíc

    if ($month < 1 || $month > 12) { // měsíc musí být 1–12
        return "Zadejte správnou platnost karty!";
    }

    $year  = (int)$match[2];
    // aktuální měsíc a rok (poslední dvě cifry)
    $currentYear  = (int)date('y');
    $currentMonth = (int)date('m');

    // rok musí být >= aktuálního roku
    if ($year < $currentYear) {
        return "Karta už není platná!";
    }

    // pokud je rok stejný jako aktuální → měsíc nesmí být menší než aktuální
    if ($year === $currentYear && $month < $currentMonth) {
        return "Karta už není platná!";
    }
    return null;
}

/**
 * Validuje CVC/CVV kód.
 *
 * @param string $cardCVV CVC/CVV kód.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkCardCVVForErrors(string $cardCVV): ?string {
    if ($error = returnErrorIfEmpty($cardCVV)) return $error;

    if (mb_strlen($cardCVV) != 3 || !ctype_digit($cardCVV)) {
        return "Zadejte správné CVC/CVV!";
    }
    return null;
}

/**
 * Ověřuje, zda uživatel souhlasí s obchodními podmínkami.
 *
 * @param bool $checkboxAgree True pokud je zaškrtnuto.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkCheckboxAgreeForErrors($checkboxAgree): ?string {
    if (!$checkboxAgree) {
        return "Musíte souhlasit s podmínkami!";
    }
    return null;
}