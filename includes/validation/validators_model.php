<?php

declare(strict_types=1);

/**
 * Vrací true, pokud e-mail existuje v databázi nebo false, pokud ne.
 *
 * @param string $email Hledaný e-mail.
 * @param PDO $pdo PDO instance.
 *
 * @return bool
 */
function checkIfEmailExistsInDB(string $email, PDO $pdo): bool {
    $query = "SELECT email FROM users WHERE email = :email LIMIT 1;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    return (bool)$stmt->fetchColumn();
}