<?php

declare(strict_types=1);

/**
 * Ověří přihlašovací údaje uživatele.
 *
 * - Najde uživatele podle e-mailu.
 * - Zkontroluje správnost hesla pomocí password_verify.
 * - Při úspěchu vrací ID a roli uživatele.
 * - Při neúspěchu vrací false.
 *
 * @param PDO $pdo PDO instance připojená k databázi.
 * @param string $email E-mail z přihlašovacího formuláře.
 * @param string $password Heslo z přihlašovacího formuláře.
 *
 * @return array{id:int, role:string}|false Asociativní pole s údaji uživatele nebo false při neplatných údajích.
 */
function authenticateUser(PDO $pdo, string $email, string $password): array|false {
    // najdeme uživatele podle e-mailu
    $query = "SELECT id, pwd_hash, role FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // e-mail neexistuje
    if (!$user) {
        return false;
    }

    // ověříme heslo pomocí password_verify
    if (!password_verify($password, $user["pwd_hash"])) {
        return false;
    }

    return [
        "id" => (int)$user["id"],
        "role" => $user["role"]
    ];
}