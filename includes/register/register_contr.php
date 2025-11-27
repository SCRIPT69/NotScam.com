<?php

/**
 * Provede kompletní validaci registračních dat.
 *
 * @param array $data Asociativní pole obsahující vstupní hodnoty formuláře.
 *
 * @return array Pole chyb, kde klíč je název pole a hodnota je text chyby.
 */
function validateRegister(array $data): array {
    $errors = [];

    if ($error = getNameError($data["name"])) {
        $errors["name"] = $error;
    }
    if ($error = getEmailError($data["email"])) {
        $errors["email"] = $error;
    }
    if ($error = getPasswordError($data["password"])) {
        $errors["password"] = $error;
    }
    if ($error = getPasswordConfirmError($data["passwordconfirm"], $data["password"])) {
        $errors["passwordconfirm"] = $error;
    }
    if ($error = getCardNumberError($data["cardnumber"])) {
        $errors["cardnumber"] = $error;
    }
    if ($error = getCardExpirationError($data["cardexpiration"])) {
        $errors["cardexpiration"] = $error;
    }
    if ($error = getCardCVVError($data["cardcvv"])) {
        $errors["cardcvv"] = $error;
    }
    if ($error = getCheckboxAgreeError($data["checkboxagree"])) {
        $errors["checkboxagree"] = $error;
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
    if (trim($value) == "") {
        return "Vyplňte pole!";
    }
}

/**
 * Validuje jméno uživatele.
 *
 * @param string $name Jméno z formuláře.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getNameError(string $name) {
    if ($error = returnErrorIfEmpty($name)) return $error;

    if (!preg_match('/^[A-Za-zÀ-ž]+(?: [A-Za-zÀ-ž]+)*$/u', $name) || mb_strlen(trim($name)) < 2) {
        return "Zadejte své pravé jméno!";
    }
}

/**
 * Validuje e-mail.
 *
 * @param string $email E-mailová adresa.
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
 * Validuje heslo podle délky a složitosti.
 *
 * @param string $password Heslo, které uživatel zadal.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getPasswordError(string $password) {
    if ($error = returnErrorIfEmpty($password)) return $error;

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
}

/**
 * Ověřuje, zda se hesla shodují.
 *
 * @param string $passwordConfirm Potvrzené heslo.
 * @param string $password Původní heslo.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getPasswordConfirmError(string $passwordConfirm, string $password) {
    if ($error = returnErrorIfEmpty($passwordConfirm)) return $error;

    if ($passwordConfirm != $password) {
        return "Hesla se neshodují!";
    }
}

/**
 * Validuje číslo platební karty.
 *
 * @param string $cardNumber Číslo karty (může obsahovat mezery).
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getCardNumberError(string $cardNumber) {
    if ($error = returnErrorIfEmpty($cardNumber)) return $error;

    // musí mít 16 znaků,
    // musí obsahovat pouze čísla
    $value = preg_replace('/\s+/', '', $cardNumber); // odstraníme všechny mezery
    if (mb_strlen($value) != 16 || !preg_match('/^\d+$/', $value)) {
        return "Zadejte platné číslo karty!";
    }
}

/**
 * Validuje expiraci platební karty.
 *
 * @param string $cardExpiration Expirace ve formátu MM/YY.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getCardExpirationError(string $cardExpiration) {
    if ($error = returnErrorIfEmpty($cardExpiration)) return $error;

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

}

/**
 * Validuje CVC/CVV kód.
 *
 * @param string $cardCVV CVC/CVV kód.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getCardCVVError(string $cardCVV) {
    if ($error = returnErrorIfEmpty($cardCVV)) return $error;

    if (mb_strlen($cardCVV) != 3 || !ctype_digit($cardCVV)) {
        return "Zadejte správné CVC/CVV!";
    }
}

/**
 * Ověřuje, zda uživatel souhlasí s obchodními podmínkami.
 *
 * @param bool $checkboxAgree True pokud je zaškrtnuto.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function getCheckboxAgreeError($checkboxAgree) {
    if (!$checkboxAgree) {
        return "Musíte souhlasit s podmínkami!";
    }
}