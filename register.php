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
                        <li><a class="burger__ul__link_chosen" href="index.php">Hlavní stránka</a></li>
                        <li><a class="burger__ul__link" href="login.php">Přihlášení</a></li>
                        <li><a class="burger__ul__link" href="register.php">Registrace</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="register">
            <h2 class="register__title">Registrace</h2>
            <form action="" method="POST" id="registerform" class="register__form">
                <div class="register__inputcontainer">
                    <label for="name" class="register__label">Jmeno</label>
                    <input type="text" id="name" name="name" class="register__input">
                    <p class="validation-error"></p>
                </div>
                <div class="register__inputcontainer">
                    <label for="email" class="register__label">E-mail</label>
                    <input type="text" id="email" name="email" class="register__input">
                    <p class="validation-error"></p>
                </div>
                <div class="register__inputcontainer">
                    <label for="password" class="register__label">Heslo</label>
                    <input type="password" id="password" name="password" class="register__input">
                    <h3 class="register__pwdinfo">Je důležité, aby heslo bylo stejné jako k e-mailu!</h3>
                    <p class="validation-error"></p>
                </div>
                <div class="register__inputcontainer">
                    <label for="password" class="register__label">Potvrďte heslo</label>
                    <input type="password" id="passwordconfirm" name="passwordconfirm" class="register__input">
                    <p class="validation-error"></p>
                </div>

                <div class="register__inputcontainer register__cardtopinputcontainer">
                    <label for="cardnumber" class="register__label">Číslo karty</label>
                    <input type="text" inputmode="numeric" id="cardnumber" name="cardnumber" class="register__input" placeholder="1234 1234 1234 1234">
                    <p class="validation-error"></p>
                </div>
                <div class="register__cardbottominputcontainer">
                    <div class="register__inputcontainer">
                        <label for="cardexpiration" class="register__label">Platnost</label>
                        <input type="text" maxlength="5" id="cardexpiration" name="cardexpiration" class="register__input register__cardbottominput" placeholder="MM/YY">
                        <p class="validation-error"></p>
                    </div>
                    <div class="register__inputcontainer">
                        <label for="cardcvv" class="register__label">CVC/CVV</label>
                        <input type="text" maxlength="3" inputmode="numeric" id="cardcvv" name="cardcvv" class="register__input register__cardbottominput" placeholder="123">
                        <p class="validation-error"></p>
                    </div>
                </div>
                <button class="register__button">Registrovat</button>
            </form>
        </section>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/burger.js"></script>
    <script type="module" src="assets/js/validation/validation_register.js"></script>
    <script src="assets/js/cardInputsFormatter.js"></script>
</body>
</html>