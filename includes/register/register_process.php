<?php
declare(strict_types=1);
/**
 * Entry point pro zpracování registračního POST formuláře.
 *
 * - Načte vstupní data.
 * - Zavolá kontroler registrace.
 * - Podle výsledku provede přesměrování.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordConfirm = $_POST["passwordconfirm"];
    // údaje o platební kartě se nikam neukládají
    // je to jen součást vtípku, skutečný podvodný web by je prostě uložil potají
    $cardNumber = $_POST["cardnumber"];
    $cardExpiration = $_POST["cardexpiration"];
    $cardCVV = $_POST["cardcvv"];
    $checkboxAgree = isset($_POST["checkboxagree"]);

    $register_data = ["name"=>$name, "email"=>$email, "password"=>$password,
        "passwordconfirm"=>$passwordConfirm, "cardnumber"=>$cardNumber,
        "cardexpiration"=>$cardExpiration, "cardcvv"=>$cardCVV,
        "checkboxagree"=>$checkboxAgree
    ];

    try {
        require_once __DIR__ . '/../dbh.php';
        require_once __DIR__ . '/../session_manager.php';
        require_once 'register_model.php';
        require_once 'register_contr.php';

        if (registerUser($register_data, $pdo)) {
            header("Location: ../../index.php");
            exit;
        }
        else {
            header("Location: ../../register.php");
            exit;
        }
    }
    catch (PDOException $e) {
        exit("Query failed: ".$e->getMessage());
    }
}
else {
    header("Location: ../../index.php");
    exit;
}