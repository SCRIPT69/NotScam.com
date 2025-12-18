<?php
require_once __DIR__ . '/includes/session_manager.php';
require_once __DIR__ . '/includes/UI/form_helpers.php';
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
                    <li><a class="header__ul__link" href="login.php">Přihlášení</a></li>
                    <li><a class="header__ul__link_chosen" href="register.php">Registrace</a></li>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <li><a class="burger__ul__link" href="index.php">Hlavní stránka</a></li>
                        <li><a class="burger__ul__link" href="login.php">Přihlášení</a></li>
                        <li><a class="burger__ul__link_chosen" href="register.php">Registrace</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="auth">
            <h2 class="auth__title">Registrace</h2>
            <form action="includes/register/register_process.php" method="POST" id="registerform" class="auth__form">
                <div class="auth__inputcontainer">
                    <label for="name" class="auth__label">Jmeno *</label>
                    <input type="text" maxlength="40" id="name" name="name" class="auth__input <?= inputErrorClass('register', 'name') ?>" value="<?= getFormData('register', 'name');?>" required>
                    <?php
                        generateErrorBlock('register', 'name');
                    ?>
                </div>
                <div class="auth__inputcontainer">
                    <label for="email" class="auth__label">E-mail *</label>
                    <input type="email" maxlength="100" id="email" name="email" class="auth__input <?= inputErrorClass('register', 'email') ?>" value="<?= getFormData('register', 'email');?>" required>
                    <?php
                        generateErrorBlock('register', 'email');
                    ?>
                </div>
                <div class="auth__inputcontainer">
                    <label for="password" class="auth__label">Heslo *</label>
                    <div class="auth__password-wrapper">
                        <input type="password" maxlength="100" id="password" name="password" class="auth__input <?= inputErrorClass('register', 'password') ?>" required>
                        <button type="button" id="showPwdButton" class="auth__password-showPwdButton">👁️</button>
                        <h3 class="auth__pwdinfo">Je důležité, aby heslo bylo stejné jako k e-mailu!</h3>
                        <?php
                            generateErrorBlock('register', 'password');
                        ?>
                    </div>
                </div>
                <div class="auth__inputcontainer">
                    <label for="passwordconfirm" class="auth__label">Potvrďte heslo *</label>
                    <input type="password" maxlength="100" id="passwordconfirm" name="passwordconfirm" class="auth__input <?= inputErrorClass('register', 'passwordconfirm') ?>" required>
                    <?php
                        generateErrorBlock('register', 'passwordconfirm');
                    ?>
                </div>

                <div class="auth__inputcontainer auth__cardtopinputcontainer">
                    <label for="cardnumber" class="auth__label">Číslo karty *</label>
                    <input type="text" maxlength="19" inputmode="numeric" id="cardnumber" name="cardnumber" class="auth__input <?= inputErrorClass('register', 'cardnumber') ?>" placeholder="1234 1234 1234 1234" value="<?= getFormData('register', 'cardnumber');?>" required>
                    <?php
                        generateErrorBlock('register', 'cardnumber');
                    ?>
                </div>
                <div class="auth__cardbottominputcontainer">
                    <div class="auth__inputcontainer">
                        <label for="cardexpiration" class="auth__label">Platnost *</label>
                        <input type="text" maxlength="5" id="cardexpiration" name="cardexpiration" class="auth__input auth__cardbottominput <?= inputErrorClass('register', 'cardexpiration') ?>" placeholder="MM/YY" value="<?= getFormData('register', 'cardexpiration');?>" required>
                        <?php
                            generateErrorBlock('register', 'cardexpiration');
                        ?>
                    </div>
                    <div class="auth__inputcontainer">
                        <label for="cardcvv" class="auth__label">CVC/CVV *</label>
                        <input type="text" maxlength="3" inputmode="numeric" id="cardcvv" name="cardcvv" class="auth__input auth__cardbottominput <?= inputErrorClass('register', 'cardcvv') ?>" placeholder="123" value="<?= getFormData('register', 'cardcvv');?>" required>
                        <?php
                            generateErrorBlock('register', 'cardcvv');
                        ?>
                    </div>
                </div>
                <div class="auth__checkboxcontainer">
                    <input class="auth__checkbox" type="checkbox" name="checkboxagree" id="checkboxagree" <?php if (getFormData('register', 'checkboxagree')) echo "checked";?>>
                    <label class="auth__checkboxlabel" for="checkboxagree">Souhlasím s <a href="conditions.php">obchodními podmínkami</a></label>
                    <?php
                        generateErrorBlock('register', 'checkboxagree');
                    ?>
                </div>
                <button class="auth__button">Registrovat</button>
            </form>
        </section>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
    <script type="module" src="assets/js/validation/validation_register.js"></script>
    <script src="assets/js/UI/showPwdButton.js"></script>
    <script src="assets/js/UI/cardInputsFormatter.js"></script>
</body>
</html>

<?php
    clearValidationSessions("register");
?>