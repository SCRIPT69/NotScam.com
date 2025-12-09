<?php
require_once 'includes/session_manager.php';
require_once 'includes/form_helpers.php';
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zaregistrujte se na NotScam.com">
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
                    <li><a class="header__ul__link_chosen" href="login.php">Přihlášení</a></li>
                    <li><a class="header__ul__link" href="register.php">Registrace</a></li>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <li><a class="burger__ul__link" href="index.php">Hlavní stránka</a></li>
                        <li><a class="burger__ul__link_chosen" href="login.php">Přihlášení</a></li>
                        <li><a class="burger__ul__link" href="register.php">Registrace</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="auth">
            <h2 class="auth__title">Přihlášení</h2>
            <form action="includes/login/login_process.php" method="POST" id="loginform" class="auth__form">
                <div class="auth__inputcontainer">
                    <label for="email" class="auth__label">E-mail *</label>
                    <input type="email" maxlength="100" id="email" name="email" class="auth__input" value="<?= getFormData('login', 'email');?>" required>
                    <?php
                        generateErrorBlock('login', 'email');
                    ?>
                </div>
                <div class="auth__inputcontainer">
                    <label for="password" class="auth__label">Heslo *</label>
                    <div class="auth__password-wrapper">
                        <input type="password" maxlength="100" id="password" name="password" class="auth__input" required>
                        <button type="button" id="showPwdButton" class="auth__password-showPwdButton">👁️</button>
                        <?php
                            generateErrorBlock('login', 'password');
                        ?>
                    </div>
                </div>
                <button class="auth__button">Přihlásit se</button>
            </form>
        </section>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
    <script type="module" src="assets/js/validation/validation_login.js"></script>
    <script src="assets/js/UI/showPwdButton.js"></script>
</body>
</html>

<?php
    clearValidationSessions("login");
?>