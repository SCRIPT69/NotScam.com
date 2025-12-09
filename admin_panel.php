<?php
require_once 'includes/session_manager.php';
if ($_SESSION["user_role"] != "admin") {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NotScam.com – nejlepší online platforma pro osobní růst a finanční svobodu. To určitě není podvod.">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/print.css" media="print">
    <link rel="icon" href="assets/img/iconlogo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    <title>NotScam.com</title>
</head>
<body>
    <header>
        <div class="header__container">
            <a href="index.php" class="logo">
                <img class="logo__icon" src="assets/img/iconlogo.png" alt="icon logo">
                <h1 class="logo__text">NotScam<span class="logo__smallerpart">.com</span></h1>
            </a>
            <nav>
                <ul class="header__ul">
                    <li><a class="header__ul__link" href="index.php">Hlavní stránka</a></li>
                    <li><a class="header__ul__link" href="profile.php">Můj profil</a></li>
                    <li><a class="header__ul__link_chosen" href="admin_panel.php">Admin panel</a></li>
                    <li><a class="header__ul__link" href="logout.php">Odhlásit se</a></li>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <li><a class="burger__ul__link" href="index.php">Hlavní stránka</a></li>
                        <li><a class="burger__ul__link" href="profile.php">Můj profil</a></li>
                        <li><a class="burger__ul__link_chosen" href="admin_panel.php">Admin panel</a></li>
                        <li><a class="burger__ul__link" href="logout.php">Odhlásit se</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>

    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
</body>
</html>