<?php
declare(strict_types=1);
/**
 * Entry point pro zpracování POST požadavku z profilu.
 * Načte uživatelská data z formuláře, předá je kontroleru a poté provede přesměrování.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $currentPassword = $_POST["currentpassword"];
    $newPassword = $_POST["newpassword"];
    $newPasswordConfirm = $_POST["newpasswordconfirm"];

    $profile_data = ["name"=>$name, "email"=>$email, "currentpassword"=>$currentPassword,
        "newpassword"=>$newPassword, "newpasswordconfirm"=>$newPasswordConfirm];

    try {
        require_once __DIR__ . '/../dbh.php';
        require_once __DIR__ . '/../session_manager.php';
        require_once 'profile_model.php';
        require_once 'profile_contr.php';

        updateProfileData($profile_data, $pdo);

        header("Location: ../../profile.php");
        exit;
    }
    catch (PDOException $e) {
        exit("Query failed: ".$e->getMessage());
    }
}
else {
    header("Location: ../../index.php");
    exit;
}