<?php

declare(strict_types=1);

/**
 * Vrací e-mail, pokud existuje v databázi.
 *
 * @param string $email Hledaný e-mail.
 * @param PDO $pdo PDO instance.
 *
 * @return array{email: string}|false Asociativní pole s e-mailem nebo false, pokud neexistuje.
 */
function getEmailIfExists(string $email, PDO $pdo): array|false {
    $query = "SELECT email FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Uloží nového uživatele do databáze.
 *
 * - Hashuje heslo pomocí algoritmu BCRYPT.
 * - Vloží jméno, e-mail a hash hesla do tabulky users.
 *
 * @param PDO $pdo PDO instance připojená k databázi.
 * @param string $name Jméno uživatele.
 * @param string $email E-mail uživatele.
 * @param string $password Původní (nezašifrované) heslo, které bude zahashováno.
 *
 * @return void
 */
function saveNewUserToDB(PDO $pdo, string $name, string $email, string $password): void {
    $query = "INSERT INTO users (name, email, pwd_hash) VALUES (:name, :email, :pwd_hash);";
    $stmt = $pdo->prepare($query);

    $options = [
        'cost' => 12
    ];
    $pwdHash = password_hash($password, PASSWORD_BCRYPT, $options);

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pwd_hash", $pwdHash);
    $stmt->execute();
}