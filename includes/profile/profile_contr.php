<?php

declare(strict_types=1);

require_once __DIR__ . '/../validation/validators.php';

/**
 * Hlavní řídicí funkce pro zpracování změn profilu.
 *
 * - Zjistí, která data byla skutečně změněna.
 * - Pokud nic změněno není - vyčistí session a končí.
 * - Provede validaci pouze změněných hodnot.
 * - Pokud validace selže - uloží chyby a částečná data zpět do session.
 * - Pokud je vše v pořádku - uloží změny do databáze.
 *
 * Tato funkce neprovádí redirect – pouze nastavuje session a provádí logiku.
 * Redirect provádí až skript profile_savingChangedData.php.
 *
 * @param array $data Všechna data z profilu odeslaná formulářem.
 * @param PDO   $pdo  PDO instance připojená k databázi.
 *
 * @return void
 */
function handleProfileUpdate(array $data, PDO $pdo): void {
    $changedData = checkWhichDataWasChangedForSaving($data, $pdo);
    // Pokud není nic změněno - jen vyčistit session a hotovo
    if (empty($changedData)) {
        unset($_SESSION["profile-errors"], $_SESSION["profile-data"]);
        return;
    }

    // Validace
    $errors = validateProfileChangedData($changedData, $pdo);
    if (!empty($errors)) {
        $_SESSION["profile-errors"] = $errors;

        if (isset($changedData["currentpassword"])) {
            unset(
                $changedData["currentpassword"],
                $changedData["newpassword"],
                $changedData["newpasswordconfirm"]
            );
        }
        $_SESSION["profile-data"] = $changedData;

        return;
    }
    unset($_SESSION["profile-errors"], $_SESSION["profile-data"]);

    saveChangedData($changedData, $pdo);

    return;
}

/**
 * Získá hodnotu zvoleného uživatelského pole z databáze podle ID uživatele v session.
 *
 * @param string $field Název pole, které se má načíst (např. 'name', 'email').
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return string Hodnota daného pole.
 */
function getUserField(string $field, PDO $pdo): string {
    $userId = $_SESSION["user_id"];
    $userField = getUserFieldById($field, (string)$userId, $pdo);
    return $userField;
}

/**
 * Zjišťuje, která data uživatel skutečně změnil oproti těm,
 * která jsou aktuálně uložená v databázi.
 *
 * Vrací pouze pole obsahující změněné hodnoty, nezměněná data se nevalidují.
 *
 * @param array $data Data z formuláře.
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return array Asociativní pole změněných hodnot.
 */
function checkWhichDataWasChangedForSaving(array $data, PDO $pdo): array {
    $changedData = [];
    $currentName = getUserField("name", $pdo);
    $currentEmail = getUserField("email", $pdo);

    if (trim($currentName) != trim($data["name"])) {
        $changedData["name"] = $data["name"];
    }
    if (trim($currentEmail) != trim($data["email"])) {
        $changedData["email"] = $data["email"];
    }
    if (trim($data["currentpassword"]) != "" || trim($data["newpassword"]) != "" || trim($data["newpasswordconfirm"]) != "") {
        $changedData["currentpassword"] = $data["currentpassword"];
        $changedData["newpassword"] = $data["newpassword"];
        $changedData["newpasswordconfirm"] = $data["newpasswordconfirm"];
    }
    return $changedData;
}

/**
 * Provede validaci pouze těch hodnot, které uživatel změnil.
 *
 * @param array $changedData Pole změněných hodnot.
 * @param PDO $pdo PDO instance.
 *
 * @return array Pole chyb, kde klíčem je název pole.
 */
function validateProfileChangedData(array $changedData, PDO $pdo): array {
    $errors = [];

    if (isset($changedData["name"])) {
        if ($error = checkNameForErrors($changedData["name"])) {
            $errors["name"] = $error;
        }
    }
    if (isset($changedData["email"])) {
        if ($error = checkEmailInProfileForErrors($changedData["email"], $pdo)) {
            $errors["email"] = $error;
        }
    }
    if (isset($changedData["currentpassword"])) {
        if ($error = checkIfCurrentPasswordCorrect($changedData["currentpassword"], $pdo)) {
            $errors["currentpassword"] = $error;
        }
        if ($error = checkNewPasswordInProfileForErrors($changedData["newpassword"], $pdo)) {
            $errors["newpassword"] = $error;
        }
        if ($error = checkPasswordConfirmForErrors($changedData["newpasswordconfirm"], $changedData["newpassword"])) {
            $errors["newpasswordconfirm"] = $error;
        }
    }

    return $errors;
}

function saveChangedData(array $changedData, PDO $pdo): void {
    $userId = (string)$_SESSION["user_id"];

    if (isset($changedData["name"])) {
        updateUserNameInDB($changedData["name"], $userId, $pdo);
    }

    if (isset($changedData["email"])) {
        updateUserEmailInDB($changedData["email"], $userId, $pdo);
    }

    if (isset($changedData["newpassword"])) {
        updateUserPasswordInDB($changedData["newpassword"], $userId, $pdo);
    }
}

/**
 * Validuje e-mail a ujistí se, že ještě nebyl použit
 *
 * @param string $email E-mailová adresa.
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkEmailInProfileForErrors(string $email, PDO $pdo): ?string {
    if ($error = checkEmailFormatForErrors($email)) return $error;
    if ($error = checkIfEmailAlreadyUsed($email, $pdo)) return $error;
    
    return null;
}

/**
 * Ověřuje, zda uživatel zadal správné aktuální heslo.
 *
 * @param string $currentPassword Heslo zadané uživatelem.
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return string|null Chybová zpráva nebo null, pokud je heslo správné.
 */
function checkIfCurrentPasswordCorrect(string $currentPassword, PDO $pdo): ?string {
    if ($error = returnErrorIfEmpty($currentPassword)) return $error;
    
    if (!comparePwdWithPwdInDB($currentPassword, (string)$_SESSION["user_id"], $pdo)) {
        return "Zadané heslo není správné!";
    }
    return null;
}

/**
 * Validuje nové heslo a zároveň zajišťuje, že není stejné jako současné.
 *
 * @param string $newPassword Nové heslo zadané uživatelem.
 * @param PDO $pdo PDO instance připojená k databázi.
 *
 * @return string|null Chybová zpráva nebo null.
 */
function checkNewPasswordInProfileForErrors(string $newPassword, PDO $pdo): ?string {
    if ($error = checkPasswordForErrors($newPassword)) return $error;

    if (comparePwdWithPwdInDB($newPassword, (string)$_SESSION["user_id"], $pdo)) {
        return "Nové heslo se musí lišit od aktuálního hesla!";
    }
    return null;
}