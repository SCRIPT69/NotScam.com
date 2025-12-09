<?php

declare(strict_types=1);

/**
 * Vrací obsah zvoleného pole uživatele podle jeho ID.
 * Povolená pole jsou whitelistem chráněna proti SQL injection.
 *
 * @param string $field Název sloupce, který se má načíst.
 * @param string $userId ID uživatele.
 * @param PDO $pdo PDO instance.
 *
 * @return string|false Hodnota sloupce nebo false, pokud nebyla nalezena.
 */
function getUserFieldById(string $field, string $userId, PDO $pdo): string|false {
    $allowed = ["name", "email"]; // whitelist
    if (!in_array($field, $allowed, true)) {
        throw new InvalidArgumentException("Invalid field name");
    }

    $query = "SELECT $field FROM users WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();
    return $stmt->fetchColumn();
}

/**
 * Porovná zadané heslo s heslem uloženým v databázi.
 *
 * @param string $pwd Čisté heslo z formuláře.
 * @param string $userId ID uživatele.
 * @param PDO $pdo PDO instance.
 *
 * @return bool True pokud heslo sedí, jinak false.
 */
function comparePwdWithPwdInDB(string $pwd, string $userId, PDO $pdo): bool {
    $query = "SELECT pwd_hash FROM users WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    $pwdHash = $stmt->fetchColumn();

    if (password_verify($pwd, $pwdHash)) {
        return true;
    }
    return false;
}

function updateUserNameInDB(string $name, string $userId, PDO $pdo): void {
    $query = "UPDATE users SET name = :name WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':name' => $name,
        ':id'   => $userId
    ]);
}

function updateUserEmailInDB(string $email, string $userId, PDO $pdo): void {
    $query = "UPDATE users SET email = :email WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':email' => $email,
        ':id'    => $userId
    ]);
}

function updateUserPasswordInDB(string $newPassword, string $userId, PDO $pdo): void {
    $pwdHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

    $query = "UPDATE users SET pwd_hash = :pwd WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':pwd' => $pwdHash,
        ':id'  => $userId
    ]);
}