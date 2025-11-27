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
                    <li><a class="header__ul__link_chosen" href="index.php">Hlavní stránka</a></li>
                    <li><a class="header__ul__link" href="login.php">Přihlášení</a></li>
                    <li><a class="header__ul__link" href="register.php">Registrace</a></li>
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
        <section class="banner">
            <h2 class="banner__text">Změň svůj<br>život už dnes!</h2>
            <h2 class="banner__secondtext">100%</h2>
        </section>
        <div class="products__container">
            <section>
                <h3 class="products__title">Zboží</h3>
                <div class="products">
                    <article class="product-card">
                        <div class="product-img"></div>
                        <h3><a class="product-text" href="">Instrukce „Jak zbohatnout za 24 hodin”</a></h3>
                        <div class="product-prevpriceblock">
                            <h3 class="product-prevprice">4200 Kč</h3>
                            <h3>-50%</h3>
                        </div>
                        <h3 class="product-price">2100 Kč</h3>
                        <a href="" class="product-button">Zobrazit</a>
                    </article>
                    <article class="product-card">
                        <div class="product-img"></div>
                        <h3><a class="product-text" href="">Kámen “Přitáhni si štěstí”</a></h3>
                        <div class="product-prevpriceblock">
                            <h3 class="product-prevprice">3500 Kč</h3>
                            <h3>-50%</h3>
                        </div>
                        <h3 class="product-price">1750 Kč</h3>
                        <a href="" class="product-button">Zobrazit</a>
                    </article>
                    <article class="product-card">
                        <div class="product-img"></div>
                        <h3><a class="product-text" href="">Kniha „Jak přesvědčit vesmír, aby ti pomohl“</a></h3>
                        <div class="product-prevpriceblock">
                            <h3 class="product-prevprice">5998 Kč</h3>
                            <h3>-50%</h3>
                        </div>
                        <h3 class="product-price">2999 Kč</h3>
                        <a href="" class="product-button">Zobrazit</a>
                    </article>
                    <article class="product-card">
                        <div class="product-img"></div>
                        <h3><a class="product-text" href="">Kámen “Přitáhni si štěstí”</a></h3>
                        <div class="product-prevpriceblock">
                            <h3 class="product-prevprice">3500 Kč</h3>
                            <h3>-50%</h3>
                        </div>
                        <h3 class="product-price">1750 Kč</h3>
                        <a href="" class="product-button">Zobrazit</a>
                    </article>
                </div>
            </section>
        </div>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
</body>
</html>